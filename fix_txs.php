<?php
$mysqli = new mysqli("127.0.0.1", "axisrym", "axisrym", "axisrym");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->query("UPDATE financial_transactions SET status = 'cancelled' WHERE deleted_at IS NOT NULL AND status != 'cancelled'");
echo "Fixed " . $mysqli->affected_rows . " transactions.\n";
