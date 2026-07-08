<?php
header('Content-Type: application/json');
require __DIR__ . '/../app/Config/Paths.php';
require __DIR__ . '/../vendor/codeigniter4/framework/system/bootstrap.php';

$db = \Config\Database::connect();
$query = $db->query("SELECT * FROM timeslot");
echo json_encode($query->getResultArray(), JSON_PRETTY_PRINT);
