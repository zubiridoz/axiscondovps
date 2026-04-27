<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToPollsTable extends Migration
{
    public function up()
    {
        $fields = [
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'General'
            ],
        ];

        if (!$this->db->fieldExists('category', 'polls')) {
            $this->forge->addColumn('polls', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('polls', 'category');
    }
}
