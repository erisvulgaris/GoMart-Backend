<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductTaxModel extends Model
{
    protected $table = 'product_taxes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'tax_id'];


    public function getProductTaxes($productId)
    {
        return $this->select('product_taxes.*, tax.tax, tax.percentage')
            ->join('tax', 'tax.id = product_taxes.tax_id')
            ->where('product_taxes.product_id', $productId)
            ->where('tax.is_active', 1)
            ->where('tax.is_delete', 0)
            ->where('tax.percentage >', 0)
            ->findAll();
    }


    public function syncProductTaxes($productId, $taxIds = [])
    {
        $this->where('product_id', $productId)->delete();

        if (!empty($taxIds)) {
            $data = [];
            foreach ($taxIds as $taxId) {
                $data[] = [
                    'product_id' => $productId,
                    'tax_id' => $taxId,
                ];
            }
            $this->insertBatch($data);
        }
    }
}
