<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAmenitySchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'amenity_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'day_of_week' => [
                'type'       => 'TINYINT',
                'unsigned'   => true,
                'comment'    => '0=Lunes, 1=Martes, ..., 6=Domingo',
            ],
            'is_enabled' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'open_time' => [
                'type'    => 'TIME',
                'default' => '09:00:00',
            ],
            'close_time' => [
                'type'    => 'TIME',
                'default' => '18:00:00',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['amenity_id', 'day_of_week']);
        $this->forge->addForeignKey('amenity_id', 'amenities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('amenity_schedules', true);
    }

    public function down()
    {
        $this->forge->dropTable('amenity_schedules', true);
    }
}
