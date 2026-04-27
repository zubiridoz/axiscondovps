<?php

namespace App\Models\Tenant;

use App\Services\TenantService;

class CondominiumModel extends BaseTenantModel
{
    protected $table            = 'condominiums';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'subscription_id',
        'name',
        'address',
        'logo',
        'cover_image',
        'currency',
        'timezone',
        'status',
        'is_billing_active',
        'billing_start_date',
        'billing_due_day',
        'owner_financial_access',
        'tenant_financial_access',
        'show_delinquent_units',
        'show_delinquency_amounts',
        'allow_resident_posts',
        'allow_post_comments',
        'always_email_posts',
        'restrict_qr_delinquent',
        'restrict_amenities_delinquent',
        'bank_name',
        'bank_clabe',
        'bank_rfc',
        'bank_card',
        'suspended_at',
        'plan_id',
        'billing_cycle',
        'plan_expires_at',
        'stripe_customer_id',
        'stripe_subscription_id',
        'payment_approval_mode',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Sobrescribimos enforceTenantScope para la tabla de condominios,
     * ya que la clave principal es 'id' y no 'condominium_id'.
     * Además, permitimos que se consulten sin tenant activo (no estricto),
     * ya que se necesitan listar para el onboarding o menús.
     */
    protected function enforceTenantScope()
    {
        $tenantService = TenantService::getInstance();
        
        if ($tenantService->hasTenant()) {
            $this->where($this->table . '.id', $tenantService->getTenantId());
        }
    }

    protected function applyTenantScope(array $data)
    {
        $this->enforceTenantScope();
        return $data;
    }

    /**
     * Sobrescribimos la inyección antes de insertar,
     * ya que la tabla de condominios no tiene columna condominium_id.
     */
    protected function injectTenantId(array $data)
    {
        return $data;
    }
}
