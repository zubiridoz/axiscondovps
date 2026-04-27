<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\UnitModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\TicketModel;
use App\Models\Tenant\PaymentModel;
use App\Models\Tenant\AccessLogModel;

/**
 * DashboardController
 * 
 * Módulo principal del Dashboard.
 * Recopila métricas clave del condominio actual, filtradas automáticamente
 * por la arquitectura multi-tenant (BaseTenantModel).
 */
class DashboardController extends BaseController
{
    public function index()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        // Cargar el condominio activo para la info del header
        $condoModel = new \App\Models\Tenant\CondominiumModel();
        $demoCondo = $condoModel->find($tenantId);

        $unitModel     = new UnitModel();
        $residentModel = new ResidentModel();
        $ticketModel   = new TicketModel();
        $paymentModel  = new PaymentModel();
        $accessModel   = new AccessLogModel();
        $financialModel = new \App\Models\Tenant\FinancialTransactionModel();
        $parcelModel = new \App\Models\Tenant\ParcelModel();
        $bookingModel = new \App\Models\Tenant\BookingModel();
        $announcementModel = new \App\Models\Tenant\AnnouncementModel();
        $calendarEventModel = new \App\Models\Tenant\CalendarEventModel();
        $qrCodeModel = new \App\Models\Tenant\QrCodeModel();

        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));

        $db = \Config\Database::connect();

        // 1. Unidades totales
        $totalUnits = $unitModel->countAllResults();

        // 2. Residentes activos (Solo asignados y activos para coincidir con el Directorio)
        $activeResidents = $db->table('residents')
            ->join('users', 'users.id = residents.user_id')
            ->join('units', 'units.id = residents.unit_id')
            ->where('residents.condominium_id', $tenantId)
            ->where('users.status', 'active')
            ->countAllResults();

        // 3. Tickets abiertos (suponiendo estados 'open', 'in_progress', etc.)
        $openTickets = $ticketModel->whereIn('status', ['open', 'in_progress'])->countAllResults();

        // Fechas exactas del mes
        $monthStart = date('Y-m-01');
        $monthEnd   = date('Y-m-t');

        // 4. Ingresos del mes en curso
        $rowIngresos = $db->query("
            SELECT IFNULL(SUM(ft.amount),0) AS total
            FROM financial_transactions ft
            INNER JOIN financial_categories c ON c.id = ft.category_id
            WHERE ft.condominium_id = ? AND ft.type = 'credit'
              AND ft.status = 'paid' AND c.type = 'income'
              AND ft.due_date BETWEEN ? AND ?
        ", [$tenantId, $monthStart, $monthEnd])->getRow();
        $ingresosMes = $rowIngresos ? (float)$rowIngresos->total : 0.0;

        // KPI Secundario: Gastos del mes (Idéntico a Panel de Control)
        $rowGastos = $db->query("
            SELECT IFNULL(SUM(ft.amount),0) AS total
            FROM financial_transactions ft
            INNER JOIN financial_categories c ON c.id = ft.category_id
            WHERE ft.condominium_id = ? AND ft.type = 'credit'
              AND ft.status = 'paid' AND c.type = 'expense'
              AND ft.due_date BETWEEN ? AND ?
        ", [$tenantId, $monthStart, $monthEnd])->getRow();
        $gastosMes = $rowGastos ? (float)$rowGastos->total : 0.0;

        // 5. Entradas hoy
        $todayVisitors = $accessModel->where('type', 'entry')->like('created_at', $today, 'after')->countAllResults();

        // 6. Publicaciones mes en curso
        $publicacionesMes = $announcementModel->like('created_at', $thisMonth, 'after')->countAllResults();

        // 7. Eventos del mes (Top)
        $eventosMes = $calendarEventModel->where('start_datetime >=', date('Y-m-01 00:00:00'))
            ->where('start_datetime <=', date('Y-m-t 23:59:59'))->countAllResults();

        // 8. Paquetes pendientes
        $paquetesPendientes = $parcelModel->whereIn('status', ['pending', 'at_gate'])->countAllResults();

        // 9. Reservas esta semana (solo reales/aprobadas)
        $reservasSemana = $bookingModel
            ->where('status', 'approved')
            ->where('start_time >=', $thisWeekStart . ' 00:00:00')
            ->where('start_time <=', $thisWeekEnd . ' 23:59:59')
            ->countAllResults();

        // 10. QR Activos
        $qrsHoy = $qrCodeModel->like('created_at', $today, 'after')->countAllResults();
        $qrsActivos = $qrCodeModel->where('status', 'active')
            ->where('valid_from <=', date('Y-m-d H:i:s'))
            ->where('valid_until >=', date('Y-m-d H:i:s'))
            ->countAllResults();

        // Condo Info
        $condoName = $demoCondo ? $demoCondo['name'] : 'Comunidad';

        $data = [
            'condo_name' => $condoName,
            'metrics' => [
                'total_units'      => $totalUnits,
                'active_residents' => $activeResidents,
                'events_month'     => $eventosMes,
                'publications_month' => $publicacionesMes,
                'pending_packages' => $paquetesPendientes,
                'income_month'     => $ingresosMes,
                'gastos_month'     => $gastosMes,
                'open_tickets'     => $openTickets,
                'reservations_week' => $reservasSemana,
                'today_visitors'   => $todayVisitors,
                'qr_active'        => $qrsActivos,
                'qr_generated_today' => $qrsHoy
            ]
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Switch Condominium (Web Panel)
     * 
     * Valida que el usuario pertenece al condominio,
     * actualiza la sesión y redirige al dashboard.
     */
    public function switchCondo(int $condominiumId)
    {
        $userId = session()->get('user_id') ?? (session()->get('user')['id'] ?? null);
        $db = \Config\Database::connect();

        if ($userId) {
            // Modo producción: validar pertenencia
            if ($condominiumId === 0) {
                // Verificar si es Super Admin (role_name = SUPER_ADMIN y condominium_id IS NULL)
                $isSuper = $db->table('user_condominium_roles')
                    ->join('roles', 'roles.id = user_condominium_roles.role_id')
                    ->where('user_condominium_roles.user_id', $userId)
                    ->where('roles.name', 'SUPER_ADMIN')
                    ->where('user_condominium_roles.condominium_id IS NULL')
                    ->countAllResults() > 0;
                    
                if (!$isSuper) {
                    log_message('warning', "[SECURITY] Web switch denegado: user={$userId} a Super Admin Global");
                    return redirect()->to(base_url('admin/dashboard'))
                        ->with('error', 'No tienes acceso de Super Administrador.');
                }
            } else {
                $pivot = $db->table('user_condominium_roles')
                    ->where('user_id', $userId)
                    ->where('condominium_id', $condominiumId)
                    ->get()
                    ->getRow();

                if (!$pivot) {
                    log_message('warning', "[SECURITY] Web switch denegado: user={$userId} a condo={$condominiumId}");
                    return redirect()->to(base_url('admin/dashboard'))
                        ->with('error', 'No tienes acceso a ese condominio.');
                }
            }
        } else {
            // Dev mode: verificar que el condominio existe
            $exists = $db->table('condominiums')
                ->where('id', $condominiumId)
                ->where('deleted_at IS NULL')
                ->countAllResults();

            if (!$exists) {
                return redirect()->to(base_url('admin/dashboard'))
                    ->with('error', 'Condominio no encontrado.');
            }
        }

        // Actualizar sesión con el nuevo condominio en ambas llaves para evitar desincronización
        session()->set('condominium_id', $condominiumId);
        session()->set('current_condominium_id', $condominiumId);
        
        \App\Services\TenantService::getInstance()->setTenantId($condominiumId);

        // Actualizar is_owner para el nuevo condominio
        if ($userId && $condominiumId > 0) {
            $ownerRow = $db->table('user_condominium_roles')
                ->where('user_id', $userId)
                ->where('condominium_id', $condominiumId)
                ->where('role_id', 2)
                ->get()->getRowArray();
            session()->set('is_owner', (int)($ownerRow['is_owner'] ?? 0));
        } else {
            session()->set('is_owner', 0);
        }

        // Obtener nombre para el flash message
        $condo = $db->table('condominiums')->where('id', $condominiumId)->get()->getRow();
        $condoName = $condo ? $condo->name : 'Condominio';

        return redirect()->to(base_url('admin/dashboard'))
            ->with('success', "Cambiado a: {$condoName}");
    }
}
