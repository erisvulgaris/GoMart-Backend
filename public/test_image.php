<?php
header('Content-Type: application/json');

$test_url = 'https://cdn.grofers.com/cdn-cgi/image/f=auto,fit=scale-down,q=70,metadata=none,w=360/da/cms-assets/cms/product/cc8401ed-66f1-4d31-9e89-d5eace0a9665.png';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $test_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

// Execute without disabling SSL first
$img_data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_error($ch);
curl_close($ch);

// Execute with disabling SSL
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $test_url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 15);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$img_data_no_ssl = curl_exec($ch2);
$http_code_no_ssl = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
$curl_err_no_ssl = curl_error($ch2);
curl_close($ch2);

// Check directory write permissions
$cache_dir = __DIR__ . '/../writable/cache/images/';
$dir_exists = is_dir($cache_dir);
$dir_writable = false;
$write_test = false;

if ($dir_exists) {
    $dir_writable = is_writable($cache_dir);
    if ($dir_writable) {
        $test_file = $cache_dir . 'test_write.txt';
        $write_test = file_put_contents($test_file, 'test') !== false;
        if ($write_test) {
            unlink($test_file);
        }
    }
} else {
    // Try to create it
    $created = mkdir($cache_dir, 0755, true);
    if ($created) {
        $dir_exists = true;
        $dir_writable = is_writable($cache_dir);
        $test_file = $cache_dir . 'test_write.txt';
        $write_test = file_put_contents($test_file, 'test') !== false;
        if ($write_test) {
            unlink($test_file);
        }
    }
}

echo json_encode([
    'curl_test_default' => [
        'http_code' => $http_code,
        'error' => $curl_err,
        'success' => (!empty($img_data) && $http_code === 200)
    ],
    'curl_test_no_ssl' => [
        'http_code' => $http_code_no_ssl,
        'error' => $curl_err_no_ssl,
        'success' => (!empty($img_data_no_ssl) && $http_code_no_ssl === 200)
    ],
    'directory_test' => [
        'path' => realpath($cache_dir) ?: $cache_dir,
        'exists' => $dir_exists,
        'writable' => $dir_writable,
        'write_test' => $write_test
    ]
]);
