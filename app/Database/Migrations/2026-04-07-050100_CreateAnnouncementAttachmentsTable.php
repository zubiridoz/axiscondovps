<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnnouncementAttachmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'announcement_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'file_name'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'original_name'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'display_name'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_type'       => ['type' => 'VARCHAR', 'constraint' => 20], // image, video, pdf
            'file_size'       => ['type' => 'BIGINT', 'unsigned' => true, 'default' => 0],
            'mime_type'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('announcement_id', 'announcements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('announcement_attachments', true);
    }

    public function down()
    {
        $this->forge->dropTable('announcement_attachments', true);
    }
}
