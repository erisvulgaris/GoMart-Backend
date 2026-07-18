<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DatabasePatch extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Update all product variants to have 100 stock and unlimited stock enabled
        $query = $db->query("UPDATE product_variants SET stock = 100, is_unlimited_stock = 1");
        
        if ($query) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Successfully updated all product variants to 100 stock and unlimited stock.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to run update query on product_variants.'
            ]);
        }
    }
}
