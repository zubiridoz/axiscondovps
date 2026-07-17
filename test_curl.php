<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.axiscondo.mx/api/v1/security/units_directory");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Condo-Id: 16',
    'Authorization: Bearer TEST'
]);
$result = curl_exec($ch);
curl_close($ch);
echo "RESPONSE:\n" . $result . "\n";
