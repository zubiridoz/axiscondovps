<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHashIdToTenantDocuments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tenant_documents', [
            'hash_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],
        ]);
        
        // Backfill existing rows
        $db = \Config\Database::connect();
        $builder = $db->table('tenant_documents');
        $rows = $builder->select('id')->get()->getResultArray();
        
        foreach ($rows as $row) {
            $builder->where('id', $row['id'])->update([
                'hash_id' => bin2hex(random_bytes(12))
            ]);
        }
        
        // Make it unique AFTER backfilling
        $db->query('ALTER TABLE tenant_documents ADD UNIQUE (hash_id)');
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE tenant_documents DROP INDEX hash_id');
        $this->forge->dropColumn('tenant_documents', 'hash_id');
    }
}
