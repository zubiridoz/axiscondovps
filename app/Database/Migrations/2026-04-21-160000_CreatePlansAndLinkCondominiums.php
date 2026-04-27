<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Extiende la tabla plans existente con columnas para gestión de unidades
 * y precios por ciclo. No destruye la estructura actual.
 */
class ExtendPlansForUnitBasedPricing extends Migration
{
    public function up()
    {
        // Agregar nuevas columnas a plans (si no existen)
        $columns = $this->db->getFieldNames('plans');

        $newFields = [];

        if (!in_array('slug', $columns)) {
            $newFields['slug'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name'];
        }
        if (!in_array('min_units', $columns)) {
            $newFields['min_units'] = ['type' => 'INT', 'unsigned' => true, 'default' => 1, 'after' => 'max_condominiums'];
        }
        if (!in_array('max_units', $columns)) {
            $newFields['max_units'] = ['type' => 'INT', 'unsigned' => true, 'default' => 50, 'after' => 'min_units'];
        }
        if (!in_array('price_monthly', $columns)) {
            $newFields['price_monthly'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00, 'after' => 'max_units'];
        }
        if (!in_array('price_yearly', $columns)) {
            $newFields['price_yearly'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00, 'after' => 'price_monthly'];
        }
        if (!in_array('features', $columns)) {
            $newFields['features'] = ['type' => 'TEXT', 'null' => true, 'after' => 'price_yearly'];
        }
        if (!in_array('is_active', $columns)) {
            $newFields['is_active'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'features'];
        }
        if (!in_array('sort_order', $columns)) {
            $newFields['sort_order'] = ['type' => 'INT', 'default' => 0, 'after' => 'is_active'];
        }

        if (!empty($newFields)) {
            $this->forge->addColumn('plans', $newFields);
        }

        // Copiar precio existente a price_monthly si existe
        $this->db->query("UPDATE plans SET price_monthly = price WHERE price_monthly = 0 AND price > 0");

        // Agregar columnas a condominiums si no existen
        $condoCols = $this->db->getFieldNames('condominiums');
        $condoFields = [];

        if (!in_array('plan_id', $condoCols)) {
            $condoFields['plan_id'] = ['type' => 'INT', 'null' => true, 'after' => 'subscription_id'];
        }
        if (!in_array('billing_cycle', $condoCols)) {
            $condoFields['billing_cycle'] = ['type' => 'ENUM', 'constraint' => ['monthly', 'yearly'], 'default' => 'monthly', 'after' => 'plan_id'];
        }
        if (!in_array('plan_expires_at', $condoCols)) {
            $condoFields['plan_expires_at'] = ['type' => 'DATETIME', 'null' => true, 'after' => 'billing_cycle'];
        }

        if (!empty($condoFields)) {
            $this->forge->addColumn('condominiums', $condoFields);
        }
    }

    public function down()
    {
        $columns = $this->db->getFieldNames('plans');
        $dropCols = array_intersect(['slug', 'min_units', 'max_units', 'price_monthly', 'price_yearly', 'features', 'is_active', 'sort_order'], $columns);
        if (!empty($dropCols)) {
            $this->forge->dropColumn('plans', $dropCols);
        }
    }
}
