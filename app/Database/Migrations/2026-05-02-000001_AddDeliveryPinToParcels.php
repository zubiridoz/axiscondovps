<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega columna delivery_pin a la tabla parcels.
 * PIN de 4 dígitos generado al registrar un paquete,
 * enviado al residente via push y requerido para confirmar la entrega.
 */
class AddDeliveryPinToParcels extends Migration
{
    public function up()
    {
        $this->forge->addColumn('parcels', [
            'delivery_pin' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => true,
                'after'      => 'signature_url',
                'comment'    => '4-digit PIN sent to resident for delivery verification',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('parcels', ['delivery_pin']);
    }
}
