<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubscriptionStatusToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'subscription_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'active',
                'null'       => false,
                'after'      => 'status', // Adding after general status
            ],
            'grace_until' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
                'after'      => 'subscription_status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'subscription_status');
        $this->forge->dropColumn('condominiums', 'grace_until');
    }
}
