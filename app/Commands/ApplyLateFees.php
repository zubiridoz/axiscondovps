<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\Tenant\CondominiumModel;
use App\Models\Tenant\FinancialCategoryModel;
use App\Models\Tenant\FinancialTransactionModel;

class ApplyLateFees extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Finance';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'latefees:apply';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Aplica cargos automáticos por mora a unidades con recibos vencidos.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'latefees:apply';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Iniciando proceso de cargos por mora...', 'blue');
        
        $db = \Config\Database::connect();
        
        // 1. Obtener todos los condominios activos con cargos por mora habilitados
        $condoModel = new CondominiumModel();
        $condominiosActivos = $condoModel->where('status', 'active')
                                         ->where('late_fee_enabled', 1)
                                         ->findAll();
                                         
        if (empty($condominiosActivos)) {
            CLI::write('No hay condominios con cargos por mora habilitados.', 'yellow');
            return;
        }

        $categoryModel = new FinancialCategoryModel();
        $totalApplied = 0;

        foreach ($condominiosActivos as $condo) {
            CLI::write("Evaluando condominio: {$condo['name']} (ID: {$condo['id']})", 'cyan');
            
            // Establecer el contexto de tenant de forma estricta para evitar bloqueos del BaseTenantModel
            \App\Services\TenantService::getInstance()->setTenantId((int)$condo['id']);

            // Decodificar categorías permitidas
            $allowedCategories = json_decode($condo['late_fee_categories'] ?? '[]', true) ?: [];
            
            // Obtener la categoría de mora del sistema
            $moraCat = $categoryModel->where(['condominium_id' => $condo['id'], 'name' => 'Cargo por Mora'])->first();
            $moraCatId = $moraCat ? $moraCat['id'] : 0;
            
            if ($moraCatId === 0) {
                // Crear la categoría si no existe
                $moraCatId = $categoryModel->insert([
                    'condominium_id' => $condo['id'],
                    'name' => 'Cargo por Mora',
                    'description' => 'Cargo automático por pago tardío',
                    'type' => 'income',
                    'is_system' => 1,
                    'is_active' => 1
                ]);
            }

            // Query estricto: Seleccionar transacciones elegibles
            $builder = $db->table('financial_transactions ft');
            $builder->select('ft.*');
            $builder->where('ft.condominium_id', $condo['id']);
            $builder->whereIn('ft.status', ['pending', 'partial']);
            $builder->where('ft.type', 'charge');
            $builder->where('ft.late_fee_applied', 0);
            
            // Aplicar periodo de gracia
            $graceDays = (int) ($condo['late_fee_grace_enabled'] ? $condo['late_fee_grace_days'] : 0);
            $builder->where("ft.due_date < DATE_SUB(CURDATE(), INTERVAL {$graceDays} DAY)", null, false);
            
            // PREVENCIÓN DE MORA RECURSIVA: Nunca aplicar mora sobre mora
            if ($moraCatId > 0) {
                $builder->where('ft.category_id !=', $moraCatId);
            }
            $builder->where('ft.source !=', 'auto');
            
            // Filtro de categorías
            if (!empty($allowedCategories)) {
                $builder->whereIn('ft.category_id', $allowedCategories);
            }

            $transaccionesVencidas = $builder->get()->getResult();
            $appliedToCondo = 0;

            foreach ($transaccionesVencidas as $txn) {
                // Cálculo de saldo preciso para evitar quiebres
                $saldoPendiente = max(0, (float)$txn->amount - (float)($txn->amount_paid ?? 0));
                
                if ($saldoPendiente <= 0) continue;

                $montoMora = 0;
                if ($condo['late_fee_type'] === 'percentage') {
                    $calc = $saldoPendiente * ((float)$condo['late_fee_percentage'] / 100);
                    $max = $condo['late_fee_max_amount'] !== null ? (float)$condo['late_fee_max_amount'] : PHP_FLOAT_MAX;
                    if ($max > 0) {
                        $montoMora = min($calc, $max);
                    } else {
                        $montoMora = $calc;
                    }
                } else {
                    $montoMora = (float) $condo['late_fee_amount'];
                }

                if ($montoMora > 0) {
                    $db->transStart();
                    
                    // 1. Insertar el cargo por mora
                    $insertSuccess = $db->table('financial_transactions')->insert([
                        'condominium_id' => $condo['id'],
                        'unit_id' => $txn->unit_id,
                        'category_id' => $moraCatId,
                        'type' => 'charge',
                        'amount' => $montoMora,
                        'description' => 'Recargo automático por cuota vencida',
                        'due_date' => date('Y-m-d'),
                        'status' => 'pending',
                        'source' => 'auto',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    // 2. Verificar éxito y actualizar origen
                    if ($insertSuccess) {
                        $db->table('financial_transactions')
                           ->where('id', $txn->id)
                           ->update(['late_fee_applied' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                    }
                    
                    $db->transComplete();
                    
                    if ($db->transStatus() !== false && $insertSuccess) {
                        $appliedToCondo++;
                        $totalApplied++;

                        // Enviar notificación Push al residente
                        $residentModel = new \App\Models\Tenant\ResidentModel();
                        $residents = $residentModel->where('unit_id', $txn->unit_id)->where('is_active', 1)->findAll();
                        
                        if (!empty($residents)) {
                            $pushService = new \App\Services\Notifications\PushNotificationService();
                            foreach ($residents as $resident) {
                                if (!empty($resident['user_id'])) {
                                    $title = 'Nuevo Cargo por Mora';
                                    $body = 'Se ha aplicado un recargo automático de $' . number_format($montoMora, 2) . ' a su estado de cuenta por un recibo vencido.';
                                    
                                    // Notificación Push (Firebase)
                                    $pushService->sendToUser(
                                        (int)$resident['user_id'], 
                                        $title, 
                                        $body,
                                        ['type' => 'finance']
                                    );
                                    
                                    // Notificación In-App (Módulo Avisos)
                                    \App\Models\Tenant\NotificationModel::notify(
                                        $condo['id'],
                                        (int)$resident['user_id'],
                                        'finance',
                                        $title,
                                        $body,
                                        ['action_url' => 'app/finances']
                                    );
                                }
                            }
                        }
                    }
                }
            }
            
            CLI::write("  - Se aplicaron {$appliedToCondo} cargos por mora.", 'green');
        }

        CLI::write("Proceso finalizado. Total de cargos aplicados globalmente: {$totalApplied}", 'blue');
    }
}
