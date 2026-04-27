<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailedFieldsToTicketsTable extends Migration
{
    public function up()
    {
        $fields = [
            'ticket_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 12,
                'null' => true,
                'after' => 'id'
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'description'
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
                'after' => 'category'
            ],
            'assigned_to_type' => [
                'type' => 'ENUM',
                'constraint' => ['user', 'staff'],
                'null' => true,
                'after' => 'priority'
            ],
            'assigned_to_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
                'after' => 'assigned_to_type'
            ],
            'due_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status'
            ],
            'tags' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'due_date'
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'tags'
            ],
            'media_urls' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'location'
            ],
        ];

        $this->forge->addColumn('tickets', $fields);
        
        // Add index to hash for quick lookups
        $this->db->query('ALTER TABLE `tickets` ADD UNIQUE INDEX `idx_ticket_hash` (`ticket_hash`)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `tickets` DROP INDEX `idx_ticket_hash`');
        
        $this->forge->dropColumn('tickets', [
            'ticket_hash',
            'category',
            'priority',
            'assigned_to_type',
            'assigned_to_id',
            'due_date',
            'tags',
            'location',
            'media_urls'
        ]);
    }
}
