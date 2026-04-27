<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToAnnouncementsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('announcements', [
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'general',
                'after'      => 'type',
            ],
            'send_email' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'is_active',
            ],
            'email_target' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'send_email',
            ],
            'view_count' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
                'after'    => 'email_target',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('announcements', ['category', 'send_email', 'email_target', 'view_count']);
    }
}
