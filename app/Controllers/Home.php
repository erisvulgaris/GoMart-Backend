<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function dump_all_products_with_details()
    {
        $db = \Config\Database::connect();
        
        $categories = $db->table('category')->select('id, category_name')->get()->getResultArray();
        $subcategories = $db->table('subcategory')->select('id, category_id, name')->get()->getResultArray();
        $products = $db->table('product')->select('id, product_name, description')->get()->getResultArray();
        
        return $this->response->setJSON([
            'categories' => $categories,
            'subcategories' => $subcategories,
            'products' => $products
        ]);
    }
}
