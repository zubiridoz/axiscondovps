<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWizardFieldsToAmenities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('amenities', [
            'reservation_interval' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => '1',
                'after'      => 'close_time',
            ],
            'max_active_reservations' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'unlimited',
                'after'      => 'reservation_interval',
            ],
            'has_cost' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'max_active_reservations',
            ],
            'requires_approval' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'has_cost',
            ],
            'available_from' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'requires_approval',
            ],
            'blocked_dates' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'available_from',
            ],
            'reservation_message' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'blocked_dates',
            ],
            'hash_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 24,
                'null'       => true,
                'after'      => 'reservation_message',
            ],
        ]);

        // Index for fast hash lookup
        $this->forge->addKey('hash_id', false, false, 'idx_amenities_hash_id');
        // Note: CI4 addKey after table creation needs raw SQL
        $this->db->query('CREATE INDEX idx_amenities_hash_id ON amenities(hash_id)');
    }

    public function down()
    {
        $this->forge->dropColumn('amenities', [
            'reservation_interval',
            'max_active_reservations',
            'has_cost',
            'requires_approval',
            'available_from',
            'blocked_dates',
            'reservation_message',
            'hash_id',
        ]);
    }
}
