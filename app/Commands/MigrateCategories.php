<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MigrateCategories extends BaseCommand {
    protected $group = 'Database';
    protected $name = 'db:migrate_categories';

    public function run(array $params) {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();
        $fields = $db->getFieldNames('financial_categories');
        
        if (!in_array('is_active', $fields)) {
            $forge->addColumn('financial_categories', [
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'after'      => 'is_system',
                ],
            ]);
            CLI::write("Column 'is_active' added.", 'green');
        }
        
        if (!in_array('icon', $fields)) {
            $forge->addColumn('financial_categories', [
                'icon' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'after'      => 'is_active',
                ],
            ]);
            CLI::write("Column 'icon' added.", 'green');
        }

        // Migrate existing icons
        $categories = $db->table('financial_categories')->get()->getResultArray();
        foreach ($categories as $cat) {
            $name = strtolower($cat['name']);
            $icon = 'bi-tag'; // Default
            if (strpos($name, 'mora') !== false) $icon = 'bi-exclamation-circle';
            elseif (strpos($name, 'reserva') !== false) $icon = 'bi-calendar-check';
            elseif (strpos($name, 'multa de amenidad') !== false) $icon = 'bi-exclamation-triangle';
            elseif (strpos($name, 'estacionamiento') !== false) $icon = 'bi-car-front';
            elseif (strpos($name, 'mascota') !== false) $icon = 'bi-bug';
            elseif (strpos($name, 'infracción') !== false || strpos($name, 'infraccion') !== false) $icon = 'bi-slash-circle';
            elseif (strpos($name, 'otro ingreso') !== false) $icon = 'bi-cash';
            elseif (strpos($name, 'salario') !== false || strpos($name, 'personal') !== false) $icon = 'bi-people';
            elseif (strpos($name, 'mantenimiento') !== false) $icon = 'bi-wrench';
            elseif (strpos($name, 'públicos') !== false || strpos($name, 'publicos') !== false) $icon = 'bi-lightning';
            elseif (strpos($name, 'suministros') !== false) $icon = 'bi-box';
            elseif (strpos($name, 'profesionales') !== false || strpos($name, 'servicios') !== false) $icon = 'bi-bag';
            elseif (strpos($name, 'seguro') !== false) $icon = 'bi-shield';
            elseif (strpos($name, 'otro') !== false) $icon = 'bi-graph-down';
            
            if ($name === 'cuota de mantenimiento') $icon = 'bi-currency-dollar';

            $db->table('financial_categories')->where('id', $cat['id'])->update(['icon' => $icon]);
        }
        CLI::write("Icons migrated successfully.", 'green');
    }
}
