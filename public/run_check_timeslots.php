<?php
header('Content-Type: application/json');
require __DIR__ . '/../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';

$app = \CodeIgniter\Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$query = $db->query("SELECT * FROM timeslot");
echo json_encode($query->getResultArray(), JSON_PRETTY_PRINT);
