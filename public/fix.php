<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

$db = \Config\Database::connect();

$iconMap = [
    'Cuota de Mantenimiento' => 'bi-cash-coin',
    'Cargo por Mora' => 'bi-exclamation-triangle',
    'Cargo de Reserva de Amenidad' => 'bi-calendar-check',
    'Multa de Amenidad' => 'bi-shield-exclamation',
    'Multa de Estacionamiento' => 'bi-car-front',
    'Multa de Mascota' => 'bi-bug',
    'Multa por Infracción' => 'bi-exclamation-octagon',
    'Otro Ingreso' => 'bi-plus-circle',
    'Salario del Personal' => 'bi-person-badge',
    'Mantenimiento y Reparaciones' => 'bi-tools',
    'Servicios Públicos' => 'bi-lightning',
    'Suministros' => 'bi-box-seam',
    'Servicios Profesionales' => 'bi-briefcase',
    'Seguro' => 'bi-shield-check',
    'Otro Gasto' => 'bi-dash-circle'
];

$updated = 0;
foreach ($iconMap as $name => $icon) {
    $db->table('financial_categories')
       ->where('name', $name)
       ->groupStart()
       ->where('icon', '')
       ->orWhere('icon IS NULL')
       ->orWhere('icon', 'bi-tag')
       ->groupEnd()
       ->update(['icon' => $icon]);
       
    $updated += $db->affectedRows();
}

echo "Updated $updated categories.\n";
