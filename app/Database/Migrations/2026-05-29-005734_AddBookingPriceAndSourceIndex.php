<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBookingPriceAndSourceIndex extends Migration
{
    public function up()
    {
        // Add booking_price column to bookings table
        $this->forge->addColumn('bookings', [
            'booking_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'null'       => false,
                'after'      => 'end_time',
            ]
        ]);

        // Add index on source column to financial_transactions table
        // Note: The CodeIgniter Forge addKey does not create the index dynamically on an existing table very well.
        // It's safer to run the raw query for the index.
        $this->db->query('CREATE INDEX idx_financial_source ON financial_transactions(source)');
    }

    public function down()
    {
        // Remove booking_price column
        $this->forge->dropColumn('bookings', 'booking_price');

        // Remove index
        $this->db->query('ALTER TABLE financial_transactions DROP INDEX idx_financial_source');
    }
}
