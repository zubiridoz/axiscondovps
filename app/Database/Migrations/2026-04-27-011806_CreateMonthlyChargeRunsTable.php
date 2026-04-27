<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMonthlyChargeRunsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'condominium_id' => ['type' => 'INT', 'unsigned' => true],
            'period'         => ['type' => 'VARCHAR', 'constraint' => 7], // Format: YYYY-MM
            'executed_at'    => ['type' => 'DATETIME', 'null' => true],
            'source'         => ['type' => 'ENUM', 'constraint' => ['cron', 'on_access'], 'default' => 'cron'],
            'status'         => ['type' => 'ENUM', 'constraint' => ['processing', 'success', 'failed'], 'default' => 'processing'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['condominium_id', 'period'], 'uq_condominium_period');
        $this->forge->addKey('status');
        
        $this->forge->createTable('monthly_charge_runs');
    }

    public function down()
    {
        $this->forge->dropTable('monthly_charge_runs');
    }
}
