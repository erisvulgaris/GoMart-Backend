<?php
header('Content-Type: application/json');

$paths = [
    'writable_logs' => WRITEPATH . 'logs',
    'writable_uploads' => WRITEPATH . 'uploads',
    'public_uploads' => FCPATH . 'uploads',
    'public_product_uploads' => FCPATH . 'uploads/product',
];

$results = [];
foreach ($paths as $key => $path) {
    if (!file_exists($path)) {
        @mkdir($path, 0777, true);
    }
    $results[$key] = [
        'path' => $path,
        'exists' => file_exists($path),
        'writable' => is_writable($path),
        'owner' => function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($path))['name'] : fileowner($path),
        'perms' => substr(sprintf('%o', fileperms($path)), -4)
    ];
}

echo json_encode($results, JSON_PRETTY_PRINT);
