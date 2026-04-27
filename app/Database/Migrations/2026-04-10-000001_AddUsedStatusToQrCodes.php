<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega el estado 'used' al ENUM de status en qr_codes.
 * Se usa para marcar un QR como completamente utilizado (ciclo entrada→salida).
 */
class AddUsedStatusToQrCodes extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE qr_codes MODIFY COLUMN status ENUM('active','expired','revoked','renovado','used') NOT NULL DEFAULT 'active'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE qr_codes MODIFY COLUMN status ENUM('active','expired','revoked','renovado') NOT NULL DEFAULT 'active'");
    }
}
