<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionSellerModel extends Model
{
    protected $table      = 'section_sellers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['section_id', 'seller_id', 'sort_order'];
    protected $returnType = 'array';

    public function getSellersBySectionId($sectionId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('section_sellers.*, seller.store_name, seller.logo')
                ->join('seller', 'seller.id = section_sellers.seller_id', 'left')
                ->where('section_sellers.section_id', $sectionId)
                ->orderBy('section_sellers.id', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function syncSellers($sectionId, $sellerIds)
    {
        $this->where('section_id', $sectionId)->delete();
        if (!empty($sellerIds)) {
            $data = [];
            foreach ($sellerIds as $index => $sellerId) {
                $data[] = [
                    'section_id' => $sectionId,
                    'seller_id'  => $sellerId,
                    'sort_order' => $index,
                ];
            }
            $this->insertBatch($data);
        }
        return true;
    }
}
