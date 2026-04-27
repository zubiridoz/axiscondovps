<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserCondominiumRolesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'condominium_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'role_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        // Regla multi-tenant restrictiva
        $this->forge->addUniqueKey(['user_id', 'condominium_id', 'role_id']);
        
        // Optimización Tenant: Buscar rápido usuarios por condominio
        $this->forge->addKey(['condominium_id', 'user_id']);
        
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('user_condominium_roles', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_condominium_roles', true);
    }
}
