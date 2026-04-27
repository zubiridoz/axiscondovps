<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStripeFieldsToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'stripe_customer_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'plan_expires_at',
            ],
            'stripe_subscription_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'stripe_customer_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'stripe_customer_id');
        $this->forge->dropColumn('condominiums', 'stripe_subscription_id');
    }
}
