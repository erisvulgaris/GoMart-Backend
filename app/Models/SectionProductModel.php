<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionProductModel extends Model
{
    protected $table      = 'section_products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['section_id', 'product_id', 'sort_order'];
    protected $returnType = 'array';

    public function getProductsBySectionId($sectionId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('section_products.*, product.product_name, product.main_img as image')
                ->join('product', 'product.id = section_products.product_id', 'left')
                ->where('section_products.section_id', $sectionId)
                ->orderBy('section_products.id', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function syncProducts($sectionId, $productIds)
    {
        // Delete existing
        $this->where('section_id', $sectionId)->delete();

        // Insert new
        if (!empty($productIds)) {
            $data = [];
            foreach ($productIds as $index => $prodId) {
                $data[] = [
                    'section_id' => $sectionId,
                    'product_id' => $prodId,
                    'sort_order' => $index,
                ];
            }
            $this->insertBatch($data);
        }
        return true;
    }
}
