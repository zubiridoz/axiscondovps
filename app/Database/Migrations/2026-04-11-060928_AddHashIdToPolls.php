<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHashIdToPolls extends Migration
{
    public function up()
    {
        // Add hash_id column
        $this->forge->addColumn('polls', [
            'hash_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 24,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        // Backfill existing rows with unique hashes
        $rows = $this->db->table('polls')->select('id')->get()->getResultArray();
        foreach ($rows as $row) {
            $this->db->table('polls')
                ->where('id', $row['id'])
                ->update(['hash_id' => bin2hex(random_bytes(12))]);
        }

        // Add unique index
        $this->db->query('ALTER TABLE polls ADD UNIQUE INDEX idx_polls_hash_id (hash_id)');
    }

    public function down()
    {
        $this->forge->dropColumn('polls', 'hash_id');
    }
}
