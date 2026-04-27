<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'amenity_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'unit_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'start_time' => [
                'type'           => 'DATETIME',
            ],
            'end_time' => [
                'type'           => 'DATETIME',
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'approved', 'rejected', 'cancelled'],
                'default'        => 'pending',
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        // Optimización Tenant para Reservas (Búsqueda de traslape calendario)
        $this->forge->addKey(['condominium_id', 'amenity_id', 'start_time']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('amenity_id', 'amenities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('bookings', true);
    }

    public function down()
    {
        $this->forge->dropTable('bookings', true);
    }
}
