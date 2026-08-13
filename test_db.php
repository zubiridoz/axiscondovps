<?php
$env = parse_ini_file('.env');
$conn = new mysqli($env['database.default.hostname'], $env['database.default.username'], $env['database.default.password'], $env['database.default.database']);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$res = $conn->query("SELECT id, vehicle_type, visit_type FROM qr_codes ORDER BY id DESC LIMIT 5");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
