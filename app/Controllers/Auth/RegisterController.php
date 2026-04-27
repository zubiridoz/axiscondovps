<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;

class RegisterController extends BaseController
{
    /**
     * Procesa la solicitud POST de registro desde la vista de Login/Register.
     * Crea un Usuario, una Suscripción Básica y un Condominio Inicial.
     */
    public function register()
    {
        // 1. Validaciones básicas
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]'
        ];

        $messages = [
            'email' => [
                'is_unique' => 'Este correo electrónico ya está registrado. Por favor inicie sesión.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors()[0] ?? 'Datos inválidos. Por favor verifique.');
        }

        $db = \Config\Database::connect();
        $now = Time::now()->toDateTimeString();

        // 2. Transacción de Base de Datos para asegurar la creación atómica del Tenant
        $db->transStart();

        try {
            // --- A. Crear al Usuario ---
            $builderUsers = $db->table('users');
            
            $userData = [
                'first_name'    => $this->request->getPost('first_name'),
                'last_name'     => $this->request->getPost('last_name'),
                'email'         => $this->request->getPost('email'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now
            ];

            $builderUsers->insert($userData);
            $userId = $db->insertID();

            // --- B. Asignar un Plan/Suscripción ---
            // Buscamos un plan activo para asignarle (el primero que encontremos, o podemos crear uno "Free" si no hay)
            $builderPlans = $db->table('plans');
            $plan = $builderPlans->where('status', 'active')->orderBy('id', 'ASC')->get()->getRow();
            
            if (!$plan) {
                // Si no hay planes en el sistema (ej. recién instalado), creamos un "Plan Básico" por defecto
                $builderPlans->insert([
                    'name' => 'Plan Básico',
                    'max_condominiums' => 1,
                    'price' => 0.00,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $planId = $db->insertID();
            } else {
                $planId = $plan->id;
            }

            // Crear Suscripción asociada al usuario
            $builderSubscriptions = $db->table('subscriptions');
            $builderSubscriptions->insert([
                'user_id'    => $userId,
                'plan_id'    => $planId,
                'starts_at'  => date('Y-m-d'),
                'expires_at' => date('Y-m-d', strtotime('+15 days')), // Damos 15 días de "Trial"
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $subscriptionId = $db->insertID();

            // --- C. Crear el Condominio (El espacio de trabajo del Tenant) ---
            $builderCondos = $db->table('condominiums');
            $condoName = "Condominio de " . $userData['first_name'] . " " . $userData['last_name'];
            $expiresAt = date('Y-m-d H:i:s', strtotime('+15 days'));
            
            $builderCondos->insert([
                'subscription_id' => $subscriptionId,
                'name'            => $condoName,
                'status'          => 'active',
                'plan_id'         => $planId,
                'plan_expires_at' => $expiresAt,
                'billing_cycle'   => 'monthly',
                'payment_method'  => 'stripe',
                'created_at'      => $now,
                'updated_at'      => $now
            ]);
            $condoId = $db->insertID();

            // --- D. Asignar el Rol de Administrador para ese Condominio ---
            $builderRoles = $db->table('roles');
            $adminRole = $builderRoles->where('name', 'ADMIN')->get()->getRow();
            
            if (!$adminRole) {
                throw new \Exception('Error Crítico: El rol ADMIN no existe en el sistema.');
            }

            $builderPivot = $db->table('user_condominium_roles');
            $builderPivot->insert([
                'user_id'        => $userId,
                'condominium_id' => $condoId,
                'role_id'        => $adminRole->id,
                'is_owner'       => 1, // Fundador de la comunidad
                'created_at'     => $now
            ]);

            // Completar transacción
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al crear la cuenta. Por favor intente más tarde.');
            }

            // Registro exitoso
            return redirect()->to('/login')->with('success', 'Cuenta creada exitosamente. Ya puede Iniciar Sesión para administrar: ' . $condoName);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
