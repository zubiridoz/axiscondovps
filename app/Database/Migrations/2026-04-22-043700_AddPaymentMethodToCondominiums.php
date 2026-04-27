<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega campo payment_method a condominiums.
 * Permite diferenciar entre cobro automático (Stripe) y manual (efectivo/transferencia).
 */
class AddPaymentMethodToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['stripe', 'manual'],
                'default'    => 'stripe',
                'after'      => 'billing_cycle',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'payment_method');
    }
}
