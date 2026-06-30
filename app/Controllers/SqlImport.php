<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SqlImport extends BaseController
{
    public function index($key = null)
    {
        $sqlPath = WRITEPATH . '../public/restore-db-grocery-ci.sql'; // Example: writable/sql/your_file.sql
        if ($key !== 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6A7B8C9D0E1F2G3H4I5J6K7L8M9N0O1P2Q3R4S5T6U7V8W9X0Y1Z2') {
            return "Unauthorized.";
        }
        if (!file_exists($sqlPath)) {
            return "SQL file not found!";
        }

        $db = \Config\Database::connect();
        $sql = file_get_contents($sqlPath);

        // Break queries if your file has multiple statements
        $queries = array_filter(array_map('trim', explode(";", $sql)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                try {
                    $db->query($query);
                } catch (\Exception $e) {
                    log_message('error', 'SQL import error: ' . $e->getMessage());
                }
            }
        }

        return "SQL file imported successfully at " . date('Y-m-d H:i:s');
    }
}
