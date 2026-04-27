<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMediaUrlsToTicketComments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('ticket_comments', [
            'media_urls' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'type',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('ticket_comments', 'media_urls');
    }
}
