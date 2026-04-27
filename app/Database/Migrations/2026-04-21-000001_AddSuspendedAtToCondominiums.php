<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega columna suspended_at para registrar timestamp de suspensión.
 * Permite timeline de eventos en el panel SuperAdmin.
 */
class AddSuspendedAtToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'suspended_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'suspended_at');
    }
}
