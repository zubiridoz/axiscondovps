<?php
require '/home/axiscondo-app/htdocs/app.axiscondo.mx/vendor/autoload.php';
$db = \Config\Database::connect();
$units = $db->table('units')->select('units.*, sections.name as section_name')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('units.condominium_id', 1)
            ->findAll();
$unitNumCounts = array_count_values(array_column($units, 'unit_number'));
print_r($unitNumCounts['801'] ?? 'Not found');
