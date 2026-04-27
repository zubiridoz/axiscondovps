<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega columna bank_card (número de tarjeta) a la tabla condominiums.
 * Migración separada para no modificar la anterior (2026-04-19-044200).
 */
class AddBankCardToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'bank_card' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => true,
                'after'      => 'bank_rfc',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'bank_card');
    }
}
