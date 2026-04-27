<?php
$db = new mysqli('localhost', 'root', 'Delfin123++', 'condominet');
if ($db->connect_error) { echo "Error: " . $db->connect_error; exit; }

// Check existing plans table
$result = $db->query("DESCRIBE plans");
if ($result) {
    echo "=== PLANS TABLE ===\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " | " . $row['Type'] . " | " . ($row['Null'] ?? '') . " | " . ($row['Key'] ?? '') . " | " . ($row['Default'] ?? 'NULL') . "\n";
    }
}

// Check plans data
$result = $db->query("SELECT * FROM plans");
if ($result) {
    echo "\n=== PLANS DATA ===\n";
    while ($row = $result->fetch_assoc()) {
        echo json_encode($row) . "\n";
    }
    echo "Total: " . $result->num_rows . " rows\n";
}

// Check subscriptions table
$result = $db->query("DESCRIBE subscriptions");
if ($result) {
    echo "\n=== SUBSCRIPTIONS TABLE ===\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " | " . $row['Type'] . " | " . ($row['Null'] ?? '') . " | " . ($row['Key'] ?? '') . " | " . ($row['Default'] ?? 'NULL') . "\n";
    }
}

// Check condominiums columns related to plans
$result = $db->query("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='condominet' AND TABLE_NAME='condominiums' AND COLUMN_NAME IN ('plan_id','billing_cycle','plan_expires_at','subscription_id')");
if ($result) {
    echo "\n=== CONDOMINIUMS PLAN COLUMNS ===\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['COLUMN_NAME'] . " | " . $row['COLUMN_TYPE'] . "\n";
    }
}

$db->close();
