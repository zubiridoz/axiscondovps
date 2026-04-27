<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega el campo `is_owner` a la tabla `user_condominium_roles`.
 * 
 * El "Owner" es el Administrador Fundador de la comunidad.
 * Los Co-Admins agregados posteriormente NO son owners.
 * 
 * Restricciones para Co-Admins (is_owner = 0):
 * - No pueden eliminar al fundador
 * - No pueden crear nuevas sociedades
 * - No pueden eliminar la comunidad
 * - No pueden gestionar suscripción/billing
 */
class AddIsOwnerToUserCondominiumRoles extends Migration
{
    public function up()
    {
        // 1. Agregar la columna
        $this->forge->addColumn('user_condominium_roles', [
            'is_owner' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'role_id',
            ],
        ]);

        // 2. Marcar como owner al PRIMER admin de cada condominio existente
        //    (el que tiene el ID más bajo por condominio con role_id = 2)
        $db = \Config\Database::connect();
        
        // Obtener el ID mínimo de cada condominio para role_id = 2 (ADMIN)
        $firstAdmins = $db->query("
            SELECT MIN(id) AS first_id
            FROM user_condominium_roles
            WHERE role_id = 2 AND condominium_id IS NOT NULL
            GROUP BY condominium_id
        ")->getResultArray();

        $ids = array_column($firstAdmins, 'first_id');
        
        if (!empty($ids)) {
            $idList = implode(',', array_map('intval', $ids));
            $db->query("UPDATE user_condominium_roles SET is_owner = 1 WHERE id IN ({$idList})");
        }

        log_message('info', '[Migration] is_owner agregado. ' . count($ids) . ' fundadores marcados.');
    }

    public function down()
    {
        $this->forge->dropColumn('user_condominium_roles', 'is_owner');
    }
}
