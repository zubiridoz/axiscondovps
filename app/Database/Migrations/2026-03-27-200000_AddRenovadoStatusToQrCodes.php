<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRenovadoStatusToQrCodes extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE qr_codes MODIFY COLUMN status ENUM('active', 'expired', 'revoked', 'renovado') DEFAULT 'active'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE qr_codes MODIFY COLUMN status ENUM('active', 'expired', 'revoked') DEFAULT 'active'");
    }
}
