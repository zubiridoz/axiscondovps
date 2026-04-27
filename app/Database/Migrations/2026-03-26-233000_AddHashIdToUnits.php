<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHashIdToUnits extends Migration
{
    public function up()
    {
        // 1. Añadir la columna hash_id
        $this->forge->addColumn('units', [
            'hash_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 24,
                'null'       => true, // Permitimos nulo temporalmente para rellenar
                'unique'     => true,
            ]
        ]);

        // 2. Rellenar los IDs existentes con una cadena hexadecimal de 24 caracteres (estilo Koti)
        $db = \Config\Database::connect();
        $builder = $db->table('units');
        $units = $builder->select('id')->get()->getResultArray();

        foreach ($units as $u) {
            $hash = bin2hex(random_bytes(12)); // 12 bytes = 24 caracteres hex
            
            // Garantizar unicidad
            while ($builder->where('hash_id', $hash)->countAllResults(false) > 0) {
                $hash = bin2hex(random_bytes(12));
            }
            
            $builder->where('id', $u['id'])->update(['hash_id' => $hash]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('units', 'hash_id');
    }
}
