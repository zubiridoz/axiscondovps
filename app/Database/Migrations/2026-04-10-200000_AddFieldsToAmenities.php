<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToAmenities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('amenities', [
            'is_reservable' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'is_active',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'null'       => true,
                'after'      => 'is_reservable',
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'price',
            ],
            'rules' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'image',
            ],
            'open_time' => [
                'type'       => 'TIME',
                'null'       => true,
                'after'      => 'rules',
            ],
            'close_time' => [
                'type'       => 'TIME',
                'null'       => true,
                'after'      => 'open_time',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('amenities', [
            'is_reservable',
            'price',
            'image',
            'rules',
            'open_time',
            'close_time',
        ]);
    }
}
