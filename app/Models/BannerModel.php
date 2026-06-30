<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    protected $allowedFields = ['home_screen_id', 
    'banner_type', 
    'content_id', 
    'redirect_url', 
    'image', 
    'status', 
    'sort_order',
    'placement'];
    protected $returnType = 'array';

    // Get all banners for admin list (all home screens)
    public function getActiveBanners()
    {
        $banners = $this->orderBy('id', 'DESC')->findAll();

        $output = [];
        $x = 1;
        foreach ($banners as $banner) {
            $output[] = [
                'number'         => $x,
                'banner_type'    => $banner['banner_type'],
                'content_id'     => $banner['content_id'],
                'image'          => $banner['image'],
                'id'             => $banner['id'],
                'status'         => $banner['status'],
                'home_screen_id' => $banner['home_screen_id'],
                'sort_order'     => $banner['sort_order'],
                'placement'      => $banner['placement'],
            ];
            $x++;
        }

        return $output;
    }

    // Get banners by home screen id for admin
    public function getBannersByHomeScreen($homeScreenId)
    {
        return $this->where('home_screen_id', $homeScreenId)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    // Get active banners by home screen id for API
    public function getActiveBannersByHomeScreen($homeScreenId)
    {
        return $this->where('home_screen_id', $homeScreenId)
                    ->where('status', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function insertBanner($data)
    {
        return $this->insert($data);
    }

    public function deleteBanner($id)
    {
        return $this->delete($id);
    }

    // Backward compatible: get banners by old status type for existing API calls
    public function getActiveBannerForApp($status)
    {
        $banners = $this->where('banner_type', $status)->where('status', 1)->findAll();
        $subcategoryModel = new \App\Models\SubcategoryModel();

        foreach ($banners as &$banner) {
            $contentId = $banner['content_id'] ?? 0;
            if ($contentId == 0) {
                $banner['firstSubcategory'] = [];
            } else {
                $banner['firstSubcategory'] = $subcategoryModel
                    ->where('category_id', $contentId)
                    ->orderBy('row_order', 'ASC')
                    ->first() ?? [];
            }
        }

        return $banners;
    }

    public function getBannersByCategory($categoryId)
    {
        return $this->where('banner_type', 'category')
            ->where('content_id', $categoryId)
            ->where('status', 1)
            ->findAll();
    }
}
