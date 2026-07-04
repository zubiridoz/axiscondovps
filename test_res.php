<?php
require '/home/axiscondo-app/htdocs/app.axiscondo.mx/vendor/autoload.php';
$app = \Config\Services::codeigniter(new \Config\App());
$app->initialize();
$db = \Config\Database::connect();
$demoCondo = $db->table('condominiums')->first();
$unitModel = clone $db->table('units');
$units = $unitModel->select('units.*, sections.name as section_name')
                   ->join('sections', 'sections.id = units.section_id', 'left')
                   ->where('units.condominium_id', (int)$demoCondo['id'])
                   ->orderBy('sections.name', 'ASC')
                   ->orderBy('units.unit_number', 'ASC')
                   ->get()->getResultArray();
$unitNumCounts = array_count_values(array_column($units, 'unit_number'));
print_r($unitNumCounts['801'] ?? 'Not found 801');
echo "\n";
print_r($unitNumCounts['202'] ?? 'Not found 202');
echo "\n";
