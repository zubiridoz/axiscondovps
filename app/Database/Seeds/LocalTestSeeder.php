<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * LocalTestSeeder
 * 
 * Inserta todos los registros pivotales para tener AxisCondo 100% operativo en Local.
 * - Crea Roles Globales
 * - Crea 1 SuperAdmin
 * - Crea 1 Condominio "Demo"
 * - Crea 1 Admin de Condominio
 * - Crea 1 Guardia / Seguridad
 * - Crea 1 Unidad en Condominio Demo
 * - Crea 1 Residente en Unidad Demo
 * 
 * Comando: php spark db:seed LocalTestSeeder
 */
class LocalTestSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builderRoles = $db->table('roles');
        $builderUsers = $db->table('users');
        $builderCondos = $db->table('condominiums');
        $builderUnits = $db->table('units');
        $builderPivot = $db->table('user_condominium_roles');
        $builderResidents = $db->table('residents');
        $builderPlans = $db->table('plans');
        $builderSubscriptions = $db->table('subscriptions');
        
        $now = Time::now()->toDateTimeString();
        $password = password_hash('password123', PASSWORD_BCRYPT);

        // ==========================================
        // 1. CREACIÓN DE ROLES CORE
        // ==========================================
        $rolesData = [
            ['name' => 'SUPER_ADMIN', 'description' => 'Dueño del SaaS'],
            ['name' => 'ADMIN',       'description' => 'Administrador de un Condominio (Tenant)'],
            ['name' => 'SECURITY',    'description' => 'Guardia de Caseta'],
            ['name' => 'RESIDENT',    'description' => 'Condómino / Habitante de Unidad']
        ];
        // Insert Ignore manual asumiendo BD limpia o para evitar choques
        foreach($rolesData as $r) {
            if ($builderRoles->where('name', $r['name'])->countAllResults() == 0) {
                $builderRoles->insert(array_merge($r, ['created_at' => $now, 'updated_at' => $now]));
            }
        }

        $superAdminRoleId = $builderRoles->where('name', 'SUPER_ADMIN')->get()->getRow()->id;
        $adminRoleId      = $builderRoles->where('name', 'ADMIN')->get()->getRow()->id;
        $securityRoleId   = $builderRoles->where('name', 'SECURITY')->get()->getRow()->id;
        $residentRoleId   = $builderRoles->where('name', 'RESIDENT')->get()->getRow()->id;


        // ==========================================
        // 2. CREACIÓN: SUPER ADMIN (SaaS Owner)
        // ==========================================
        $superadminData = [
            'first_name'    => 'Super',
            'last_name'     => 'Admin',
            'email'         => 'superadmin@axiscondo.local',
            'password_hash' => $password,
            'status'        => 'active',
            'created_at'    => $now,
            'updated_at'    => $now
        ];
        $superadminUser = $builderUsers->where('email', $superadminData['email'])->get()->getRow();
        if (!$superadminUser) {
            $builderUsers->insert($superadminData);
            $superAdminUserId = $db->insertID();
            $builderPivot->insert([
                'user_id' => $superAdminUserId, 'condominium_id' => null, 'role_id' => $superAdminRoleId, 'created_at' => $now
            ]);
        } else {
            $superAdminUserId = $superadminUser->id;
        }


        // ==========================================
        // 2.5. CREACIÓN: PLAN DEMO
        // ==========================================
        $planData = [
            'name' => 'Plan Pro Demo',
            'max_condominiums' => 1,
            'price' => 999.00,
            'status' => 'active',
            'created_at' => $now, 'updated_at' => $now
        ];
        $plan = $builderPlans->where('name', $planData['name'])->get()->getRow();
        if (!$plan) {
            $builderPlans->insert($planData);
            $planId = $db->insertID();
        } else {
            $planId = $plan->id;
        }

        // ==========================================
        // 2.6. CREACIÓN: ADMINISTRADOR (Paso 1: Usuario)
        // ==========================================
        $adminData = [
            'first_name'    => 'John',
            'last_name'     => 'Manager',
            'email'         => 'admin@demo.com',
            'password_hash' => $password,
            'status'        => 'active',
            'created_at'    => $now,
            'updated_at'    => $now
        ];
        $adminUser = $builderUsers->where('email', $adminData['email'])->get()->getRow();
        if (!$adminUser) {
            $builderUsers->insert($adminData);
            $adminUserId = $db->insertID();
        } else {
            $adminUserId = $adminUser->id;
        }

        // ==========================================
        // 2.7. CREACIÓN: SUSCRIPCION DEMO
        // ==========================================
        $subData = [
            'user_id' => $adminUserId,
            'plan_id' => $planId,
            'starts_at' => date('Y-m-d'),
            'expires_at' => date('Y-m-d', strtotime('+1 year')),
            'status' => 'active',
            'created_at' => $now, 'updated_at' => $now
        ];
        $subscription = $builderSubscriptions->where('user_id', $adminUserId)->get()->getRow();
        if (!$subscription) {
            $builderSubscriptions->insert($subData);
            $subscriptionId = $db->insertID();
        } else {
            $subscriptionId = $subscription->id;
        }

        // ==========================================
        // 3. CREACIÓN: CONDOMINIO DEMO (Tenant Principal)
        // ==========================================
        $condoData = [
            'subscription_id' => $subscriptionId,
            'name'       => 'Condominio Demo',
            'address'    => 'Av. Test Local 123',
            'status'     => 'active',
            'created_at' => $now,
            'updated_at' => $now
        ];
        $demoCondo = $builderCondos->where('name', $condoData['name'])->get()->getRow();
        if (!$demoCondo) {
            $builderCondos->insert($condoData);
            $demoCondoId = $db->insertID();
        } else {
            $demoCondoId = $demoCondo->id;
        }

        // ==========================================
        // 4. CREACIÓN: ASIGNAR ADMIN AL CONDO (Pivot)
        // ==========================================
        $pivotAdmin = $builderPivot->where(['user_id' => $adminUserId, 'condominium_id' => $demoCondoId])->get()->getRow();
        if (!$pivotAdmin && !$adminUser) { // only insert if adminUser was newly created just now, to prevent duplicates
            $builderPivot->insert([
                'user_id' => $adminUserId, 'condominium_id' => $demoCondoId, 'role_id' => $adminRoleId, 'created_at' => $now
            ]);
        }


        // ==========================================
        // 5. CREACIÓN: UNIDAD EN CONDOMINIO DEMO
        // ==========================================
        $unitData = [
            'condominium_id' => $demoCondoId,
            'section_id'     => null,
            'unit_number'    => 'A101',
            'created_at'     => $now,
            'updated_at'     => $now
        ];
        $demoUnit = $builderUnits->where(['condominium_id' => $demoCondoId, 'unit_number' => $unitData['unit_number']])->get()->getRow();
        if (!$demoUnit) {
            $builderUnits->insert($unitData);
            $demoUnitId = $db->insertID();
        } else {
            $demoUnitId = $demoUnit->id;
        }


        // ==========================================
        // 6. CREACIÓN: RESIDENTE Y GUARDIA (PARA PWAS)
        // ==========================================
        
        // --- RESIDENTE ---
        $residentUserData = [
            'first_name'    => 'Jane',
            'last_name'     => 'Tenant',
            'email'         => 'resident@demo.com',
            'password_hash' => $password,
            'phone'         => '5551234567',
            'status'        => 'active',
            'created_at'    => $now, 'updated_at' => $now
        ];
        $residentUser = $builderUsers->where('email', $residentUserData['email'])->get()->getRow();
        if (!$residentUser) {
            $builderUsers->insert($residentUserData);
            $residentUserId = $db->insertID();
            $builderPivot->insert([
                'user_id' => $residentUserId, 'condominium_id' => $demoCondoId, 'role_id' => $residentRoleId, 'created_at' => $now
            ]);
            $builderResidents->insert([
                'condominium_id' => $demoCondoId,
                'user_id'        => $residentUserId,
                'unit_id'        => $demoUnitId,
                'type'           => 'owner',
                'created_at'     => $now, 'updated_at' => $now
            ]);
        }


        // --- GUARDIA CASETA ---
        $guardUserData = [
            'first_name'    => 'Officer',
            'last_name'     => 'Vigilance',
            'email'         => 'guard@demo.com',
            'password_hash' => $password,
            'status'        => 'active',
            'created_at'    => $now, 'updated_at' => $now
        ];
        $guardUser = $builderUsers->where('email', $guardUserData['email'])->get()->getRow();
        if (!$guardUser) {
            $builderUsers->insert($guardUserData);
            $guardUserId = $db->insertID();
            $builderPivot->insert([
                'user_id' => $guardUserId, 'condominium_id' => $demoCondoId, 'role_id' => $securityRoleId, 'created_at' => $now
            ]);
        }

        echo "\n[OK] Datos Locales Insertados Correctamente.\n";
    }
}
