<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowNullUnitIdInBookings extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE bookings MODIFY unit_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE bookings MODIFY unit_id BIGINT UNSIGNED NOT NULL');
    }
}
