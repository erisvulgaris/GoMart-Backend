<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionBrandModel extends Model
{
    protected $table      = 'section_brands';
    protected $primaryKey = 'id';
    protected $allowedFields = ['section_id', 'brand_id', 'sort_order'];
    protected $returnType = 'array';

    public function getBrandsBySectionId($sectionId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('section_brands.*, brand.brand as brand_name, brand.image')
                ->join('brand', 'brand.id = section_brands.brand_id', 'left')
                ->where('section_brands.section_id', $sectionId)
                ->orderBy('section_brands.id', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function syncBrands($sectionId, $brandIds)
    {
        $this->where('section_id', $sectionId)->delete();
        if (!empty($brandIds)) {
            $data = [];
            foreach ($brandIds as $index => $brandId) {
                $data[] = [
                    'section_id' => $sectionId,
                    'brand_id'   => $brandId,
                    'sort_order' => $index,
                ];
            }
            $this->insertBatch($data);
        }
        return true;
    }
}
