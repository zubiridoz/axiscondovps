<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouriersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Identifier for SVG icon rendering on frontend',
            ],
            'sort_order' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'is_active' => [
                'type'    => 'TINYINT',
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('couriers', true);

        // Seed default couriers
        $db = \Config\Database::connect();
        $couriers = [
            ['name' => 'Mercado Libre',      'icon' => 'mercadolibre',  'sort_order' => 1],
            ['name' => 'Amazon',             'icon' => 'amazon',        'sort_order' => 2],
            ['name' => 'DHL',                'icon' => 'dhl',           'sort_order' => 3],
            ['name' => 'FedEx',              'icon' => 'fedex',         'sort_order' => 4],
            ['name' => 'Estafeta',           'icon' => 'estafeta',      'sort_order' => 5],
            ['name' => 'UPS',                'icon' => 'ups',           'sort_order' => 6],
            ['name' => 'Redpack',            'icon' => 'redpack',       'sort_order' => 7],
            ['name' => '99 Minutos',         'icon' => '99minutos',     'sort_order' => 8],
            ['name' => 'Correos de México',  'icon' => 'correosmx',     'sort_order' => 9],
            ['name' => 'Paquetexpress',      'icon' => 'paquetexpress', 'sort_order' => 10],
            ['name' => 'Otro',               'icon' => 'otro',          'sort_order' => 99],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($couriers as $c) {
            $c['is_active'] = 1;
            $c['created_at'] = $now;
            $c['updated_at'] = $now;
            $db->table('couriers')->insert($c);
        }
    }

    public function down()
    {
        $this->forge->dropTable('couriers', true);
    }
}
