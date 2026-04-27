<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStripePricesToPlans extends Migration
{
    public function up()
    {
        $this->forge->addColumn('plans', [
            'stripe_price_id_monthly' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'price_yearly',
            ],
            'stripe_price_id_yearly' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'stripe_price_id_monthly',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('plans', 'stripe_price_id_monthly');
        $this->forge->dropColumn('plans', 'stripe_price_id_yearly');
    }
}
