<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table      = 'sections';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'home_screen_id', 'title', 'short_title', 'description', 'section_style',
        'content_type', 'product_content_type', 'section_type', 'product_type',
        'category_id', 'sub_category_id', 'brand_id', 'seller_id',
        'selling_type', 'price_min', 'price_max', 'sort_by', 'screen_layout',
        'no_of_content', 'no_of_row', 'view_all', 'load_more',
        'order_by_upload', 'order_by_like',
        'background_type', 'bg_color', 'bg_image',
        'status', 'sort_order'
    ];

    // section_style values:
    // category_list   - Show categories (all or manually selected)
    // best_seller     - Best seller categories (all or manually selected)
    // product_list    - Product list (dynamic by category/brand/seller or manually selected)
    // highlight       - Highlight cards (all or manually selected)
    // shop_by_brand   - Brand list (all or manually selected)
    // shop_by_seller  - Seller list (all or manually selected)
    protected $returnType = 'array';

    public function getSectionsByHomeScreen($homeScreenId)
    {
        return $this->where('home_screen_id', $homeScreenId)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function getActiveSectionsByHomeScreen($homeScreenId)
    {
        return $this->where('home_screen_id', $homeScreenId)
                    ->where('status', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function insertSection($data)
    {
        return $this->insert($data);
    }

    public function deleteSection($id)
    {
        return $this->delete($id);
    }

    public function updateSortOrder($items)
    {
        foreach ($items as $item) {
            $this->update($item['id'], ['sort_order' => $item['sort_order']]);
        }
        return true;
    }
}
