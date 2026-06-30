<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderProductTaxModel extends Model
{
    protected $table = 'order_product_taxes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_product_id', 'tax_name', 'tax_percentage', 'tax_amount'];

    public function getTaxBreakdown($orderProductId)
    {
        return $this->where('order_product_id', $orderProductId)->findAll();
    }

    public function getTaxBreakdownByOrderProducts($orderProductIds)
    {
        if (empty($orderProductIds)) return [];
        return $this->whereIn('order_product_id', $orderProductIds)->findAll();
    }
}
