<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Apple App Store Guideline 1.2 — Content Moderation Tables
 *
 * Creates:
 *  - content_reports: stores user reports of offensive content (posts/comments)
 *  - blocked_users:   stores user-to-user blocks for feed filtering
 */
class CreateContentModerationTables extends Migration
{
    public function up()
    {
        // ── content_reports ──────────────────────────────────────
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'reporter_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'User who filed the report',
            ],
            'reported_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Author of the reported content',
            ],
            'announcement_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Reported post (nullable)',
            ],
            'comment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Reported comment (nullable)',
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'spam|harassment|offensive|misinformation|other',
            ],
            'description' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Optional description by reporter',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
                'comment'    => 'pending|reviewed|dismissed',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('condominium_id');
        $this->forge->addKey('reporter_user_id');
        $this->forge->addKey('reported_user_id');
        $this->forge->addKey('announcement_id');
        $this->forge->addKey('comment_id');
        $this->forge->createTable('content_reports', true);

        // ── blocked_users ────────────────────────────────────────
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'User who is blocking',
            ],
            'blocked_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'User who is being blocked',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['condominium_id', 'user_id']);
        $this->forge->addKey(['condominium_id', 'blocked_user_id']);
        $this->forge->addKey(['user_id', 'blocked_user_id'], false, true); // unique pair
        $this->forge->createTable('blocked_users', true);
    }

    public function down()
    {
        $this->forge->dropTable('content_reports', true);
        $this->forge->dropTable('blocked_users', true);
    }
}
