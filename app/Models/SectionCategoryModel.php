<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionCategoryModel extends Model
{
    protected $table      = 'section_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['section_id', 'category_id', 'sort_order'];
    protected $returnType = 'array';

    public function getCategoriesBySectionId($sectionId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('section_categories.*, category.category_name, category.category_img')
                ->join('category', 'category.id = section_categories.category_id', 'left')
                ->where('section_categories.section_id', $sectionId)
                ->orderBy('section_categories.id', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function syncCategories($sectionId, $categoryIds)
    {
        // Delete existing
        $this->where('section_id', $sectionId)->delete();

        // Insert new
        if (!empty($categoryIds)) {
            $data = [];
            foreach ($categoryIds as $index => $catId) {
                $data[] = [
                    'section_id'  => $sectionId,
                    'category_id' => $catId,
                    'sort_order'  => $index,
                ];
            }
            $this->insertBatch($data);
        }
        return true;
    }
}
