<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\SectionModel;
use App\Models\SectionCategoryModel;
use App\Models\SectionProductModel;
use App\Models\SectionHighlightModel;
use App\Models\SectionBrandModel;
use App\Models\SectionSellerModel;
use App\Models\HomeScreenModel;
use App\Models\CategoryModel;
use App\Models\BrandModel;
use App\Models\SellerModel;
use App\Models\HighlightsModel;
use App\Models\SubcategoryModel;
use App\Models\SettingsModel;

class Section extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('home-section')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel        = new SettingsModel();
            $homeScreenModel     = new HomeScreenModel();
            $categoryModel       = new CategoryModel();
            $brandModel          = new BrandModel();
            $sellerModel         = new SellerModel();
            $highlightsModel     = new HighlightsModel();
            $subcategoryModel    = new SubcategoryModel();

            return view('section/index', [
                'settings'           => $settingModel->getSettings(),
                'homeScreens'        => $homeScreenModel->getAllHomeScreens(),
                'categories'         => $categoryModel->getCategories(),
                'bestSellerCategories' => \Config\Database::connect()->table('category')->where('is_bestseller_category', 1)->get()->getResultArray(),
                'brands'             => $brandModel->getBrandList(),
                'sellers'            => $sellerModel->findAll(),
                'highlights'         => $highlightsModel->getActiveHighlights(),
                'subcategories'      => $subcategoryModel->findAll(),
            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    public function list()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_view('home-section')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not allowed']);
        }

        $homeScreenId = $this->request->getPost('home_screen_id');
        if (empty($homeScreenId)) {
            return $this->response->setJSON(['success' => false, 'data' => []]);
        }

        $sectionModel         = new SectionModel();
        $sectionCategoryModel = new SectionCategoryModel();
        $sectionProductModel  = new SectionProductModel();
        $sectionHighlightModel = new SectionHighlightModel();
        $sectionBrandModel    = new SectionBrandModel();
        $sectionSellerModel   = new SectionSellerModel();

        $sections = $sectionModel->getSectionsByHomeScreen($homeScreenId);
        $db       = \Config\Database::connect();

        foreach ($sections as &$section) {
            $style       = $section['section_style'] ?? 'category_list';
            $sectionType = (int)($section['section_type'] ?? 0);

            if ($sectionType == 1) {
                // Manual — count pinned items
                $section['live_count'] = match ($style) {
                    'category_list', 'best_seller' => $sectionCategoryModel->where('section_id', $section['id'])->countAllResults(),
                    'product_list'                 => $sectionProductModel->where('section_id', $section['id'])->countAllResults(),
                    'highlight'                    => $sectionHighlightModel->where('section_id', $section['id'])->countAllResults(),
                    'shop_by_brand'                => $sectionBrandModel->where('section_id', $section['id'])->countAllResults(),
                    'shop_by_seller'               => $sectionSellerModel->where('section_id', $section['id'])->countAllResults(),
                    default                        => 0,
                };
                $section['manual_items'] = $section['live_count'];
            } else {
                // Dynamic — count what would actually render
                $section['manual_items'] = 0;
                $section['live_count']   = match ($style) {
                    'category_list' => $this->_countCategories($db, $section),
                    'best_seller'   => $this->_countBestSellerCategories($db, $section),
                    'product_list'  => $this->_countProducts($db, $section),
                    'highlight'     => $this->_countHighlights($db, $section),
                    'shop_by_brand' => $this->_countBrands($db, $section),
                    'shop_by_seller' => $this->_countSellers($db, $section),
                    default         => 0,
                };
            }
        }

        return $this->response->setJSON(['success' => true, 'data' => $sections]);
    }

    // ─── Live-count helpers ────────────────────────────────────────────────────

    private function _countCategories($db, $section)
    {
        $builder = $db->table('category');
        if (!empty($section['category_id'])) {
            $builder->where('id', $section['category_id']);
        }
        return $builder->countAllResults();
    }

    private function _countBestSellerCategories($db, $section)
    {
        $builder = $db->table('category')->where('is_bestseller_category', 1);
        if (!empty($section['category_id'])) {
            $builder->where('id', $section['category_id']);
        }
        return $builder->countAllResults();
    }

    private function _countProducts($db, $section)
    {
        $builder = $db->table('product p')
            ->select('COUNT(DISTINCT p.id) as cnt')
            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'inner')
            ->where('p.status', 1)
            ->where('p.is_delete', 0);

        if (!empty($section['category_id'])) {
            $builder->join('product_categories pc', 'pc.product_id = p.id', 'inner');
            $builder->where('pc.category_id', $section['category_id']);
        }

        if (!empty($section['sub_category_id'])) {
            $builder->join('product_subcategories ps', 'ps.product_id = p.id', 'inner');
            $builder->where('ps.subcategory_id', $section['sub_category_id']);
        }

        if (!empty($section['brand_id'])) {
            $builder->where('p.brand_id',  $section['brand_id']);
        }
        if (!empty($section['seller_id'])) {
            $builder->where('p.seller_id', $section['seller_id']);
        }

        $row = $builder->get()->getRowArray();
        return (int)($row['cnt'] ?? 0);
    }

    private function _countHighlights($db, $section)
    {
        return $db->table('highlights')->where('is_active', 1)->countAllResults();
    }

    private function _countBrands($db, $section)
    {
        $builder = $db->table('product p')
            ->select('COUNT(DISTINCT p.brand_id) as cnt')
            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'inner')
            ->where('p.status', 1)->where('p.is_delete', 0)->where('p.brand_id !=', 0);
        $row = $builder->get()->getRowArray();
        return (int)($row['cnt'] ?? 0);
    }

    private function _countSellers($db, $section)
    {
        $builder = $db->table('product p')
            ->select('COUNT(DISTINCT p.seller_id) as cnt')
            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'inner')
            ->where('p.status', 1)->where('p.is_delete', 0);
        $row = $builder->get()->getRowArray();
        return (int)($row['cnt'] ?? 0);
    }

    // ─── Add ──────────────────────────────────────────────────────────────────

    public function add()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_add('home-section')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not allowed']);
        }
        if ($this->settings['demo_mode']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Demo Mode! Permission not allowed']);
        }

        $homeScreenId = $this->request->getPost('home_screen_id');
        $title        = $this->request->getPost('title');
        $style        = $this->request->getPost('section_style') ?: 'category_list';

        if (empty($homeScreenId) || empty($title)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Home Screen and Title are required']);
        }

        $sectionType = (int)$this->request->getPost('section_type');

        // Determine content_type from style
        $contentType = match ($style) {
            'category_list', 'best_seller' => 1,
            'product_list', 'shop_by_brand', 'shop_by_seller' => 2,
            'highlight' => 3,
            default => 1,
        };

        // Fixed 1 row for highlight/shop_by_brand/shop_by_seller
        $noOfRow = in_array($style, ['highlight', 'shop_by_brand', 'shop_by_seller'])
            ? 1
            : (int)($this->request->getPost('no_of_row') ?: 1);

        $data = [
            'home_screen_id' => (int)$homeScreenId,
            'title'          => $title,
            'short_title'    => $this->request->getPost('short_title'),
            'description'    => $this->request->getPost('description'),
            'section_style'  => $style,
            'content_type'   => $contentType,
            'section_type'   => $sectionType,
            'category_id'    => $style === 'product_list' ? ($this->request->getPost('category_id') ?: null) : null,
            'sub_category_id' => $style === 'product_list' ? ($this->request->getPost('sub_category_id') ?: null) : null,
            'brand_id'       => $style === 'product_list' ? ($this->request->getPost('brand_id') ?: null) : null,
            'seller_id'      => $style === 'product_list' ? ($this->request->getPost('seller_id') ?: null) : null,
            'sort_by'        => $style === 'product_list' ? ($this->request->getPost('sort_by') ?: 'default') : null,
            'no_of_content'  => (int)($this->request->getPost('no_of_content') ?: 10),
            'no_of_row'      => $noOfRow,
            'view_all'       => (int)($this->request->getPost('view_all') ?: 0),
            'load_more'      => (int)($this->request->getPost('load_more') ?: 0),
            'background_type' => 'color',
            'bg_color'       => $this->request->getPost('bg_color') ?: '#FFFFFF',
            'bg_image'       => null,
            'status'         => (int)($this->request->getPost('status') ?? 1),
            'sort_order'     => 0,
            // legacy fields — reset to neutral
            'product_content_type' => null,
            'product_type'         => null,
            'selling_type'         => null,
            'price_min'            => 0,
            'price_max'            => 0,
            'order_by_upload'      => 1,
            'order_by_like'        => 1,
            'screen_layout'        => 'potrait_item',
        ];

        $sectionModel = new SectionModel();
        $sectionId    = $sectionModel->insertSection($data);

        if ($sectionId) {
            $this->_syncManualItems($style, $sectionType, $sectionId);
            $output = ['success' => true, 'message' => 'Section added successfully!'];
        } else {
            $output['message'] = 'Unable to add section';
        }

        return $this->response->setJSON($output);
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_edit('home-section')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not allowed']);
        }
        if ($this->settings['demo_mode']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Demo Mode! Permission not allowed']);
        }

        $sectionId = $this->request->getPost('section_id');
        $title     = $this->request->getPost('title');
        $style     = $this->request->getPost('section_style') ?: 'category_list';

        if (empty($sectionId) || empty($title)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Section ID and Title are required']);
        }

        $sectionType = (int)$this->request->getPost('section_type');

        $contentType = match ($style) {
            'category_list', 'best_seller' => 1,
            'product_list', 'shop_by_brand', 'shop_by_seller' => 2,
            'highlight' => 3,
            default => 1,
        };

        $noOfRow = in_array($style, ['highlight', 'shop_by_brand', 'shop_by_seller'])
            ? 1
            : (int)($this->request->getPost('no_of_row') ?: 1);

        $data = [
            'title'          => $title,
            'short_title'    => $this->request->getPost('short_title'),
            'description'    => $this->request->getPost('description'),
            'section_style'  => $style,
            'content_type'   => $contentType,
            'section_type'   => $sectionType,
            'category_id'    => $style === 'product_list' ? ($this->request->getPost('category_id') ?: null) : null,
            'sub_category_id' => $style === 'product_list' ? ($this->request->getPost('sub_category_id') ?: null) : null,
            'brand_id'       => $style === 'product_list' ? ($this->request->getPost('brand_id') ?: null) : null,
            'seller_id'      => $style === 'product_list' ? ($this->request->getPost('seller_id') ?: null) : null,
            'sort_by'        => $style === 'product_list' ? ($this->request->getPost('sort_by') ?: 'default') : null,
            'no_of_content'  => (int)($this->request->getPost('no_of_content') ?: 10),
            'no_of_row'      => $noOfRow,
            'view_all'       => (int)($this->request->getPost('view_all') ?: 0),
            'load_more'      => (int)($this->request->getPost('load_more') ?: 0),
            'bg_color'       => $this->request->getPost('bg_color') ?: '#FFFFFF',
            'status'         => (int)($this->request->getPost('status') ?? 1),
        ];

        $sectionModel = new SectionModel();

        if ($sectionModel->where('id', $sectionId)->set($data)->update()) {
            $this->_syncManualItems($style, $sectionType, $sectionId);
            $output = ['success' => true, 'message' => 'Section updated.'];
        } else {
            $output['message'] = 'Database error occurred.';
        }

        return $this->response->setJSON($output);
    }

    // ─── Sync manual items (shared by add/update) ─────────────────────────────

    private function _syncManualItems($style, $sectionType, $sectionId)
    {
        if ($sectionType != 1) return; // Dynamic — nothing to sync

        $raw = function ($key) {
            $val = $this->request->getPost($key);
            if (empty($val)) return [];
            return is_array($val) ? $val : explode(',', $val);
        };

        switch ($style) {
            case 'category_list':
            case 'best_seller':
                (new SectionCategoryModel())->syncCategories($sectionId, $raw('manual_category_ids'));
                break;
            case 'product_list':
                (new SectionProductModel())->syncProducts($sectionId, $raw('manual_product_ids'));
                break;
            case 'highlight':
                (new SectionHighlightModel())->syncHighlights($sectionId, $raw('manual_highlight_ids'));
                break;
            case 'shop_by_brand':
                (new SectionBrandModel())->syncBrands($sectionId, $raw('manual_brand_ids'));
                break;
            case 'shop_by_seller':
                (new SectionSellerModel())->syncSellers($sectionId, $raw('manual_seller_ids'));
                break;
        }
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    public function delete()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_delete('home-section')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not allowed']);
        }
        if ($this->settings['demo_mode']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Demo Mode! Permission not allowed']);
        }

        $sectionId = $this->request->getPost('section_id');
        if ($sectionId) {
            $sectionModel = new SectionModel();
            if ($sectionModel->deleteSection($sectionId)) {
                $output = ['success' => true, 'message' => 'Section deleted successfully!'];
            } else {
                $output['message'] = 'Something went wrong';
            }
        }

        return $this->response->setJSON($output);
    }

    // ─── Toggle Status ────────────────────────────────────────────────────────

    public function toggleStatus()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_edit('home-section')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not allowed']);
        }

        $sectionId = $this->request->getPost('section_id');
        $status    = $this->request->getPost('status');

        if ($sectionId !== null) {
            $sectionModel = new SectionModel();
            if ($sectionModel->where('id', $sectionId)->set(['status' => (int)$status])->update()) {
                $output = ['success' => true, 'message' => 'Status updated.'];
            }
        }

        return $this->response->setJSON($output);
    }

    // ─── Update Sort Order ────────────────────────────────────────────────────

    public function updateSortOrder()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_edit('home-section')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not allowed']);
        }

        $items = $this->request->getPost('items');
        if (!empty($items)) {
            (new SectionModel())->updateSortOrder($items);
            $output = ['success' => true, 'message' => 'Sort order updated.'];
        }

        return $this->response->setJSON($output);
    }

    // ─── Get Manual Items (edit modal population) ─────────────────────────────

    public function getManualItems()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }

        $sectionId = $this->request->getPost('section_id');
        $style     = $this->request->getPost('section_style');

        $result = match ($style) {
            'category_list', 'best_seller' => (new SectionCategoryModel())->getCategoriesBySectionId($sectionId),
            'product_list'                 => (new SectionProductModel())->getProductsBySectionId($sectionId),
            'highlight'                    => (new SectionHighlightModel())->getHighlightsBySectionId($sectionId),
            'shop_by_brand'                => (new SectionBrandModel())->getBrandsBySectionId($sectionId),
            'shop_by_seller'               => (new SectionSellerModel())->getSellersBySectionId($sectionId),
            default                        => [],
        };

        return $this->response->setJSON($result);
    }

    // ─── Product Search ───────────────────────────────────────────────────────

    public function searchProducts()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return $this->response->setJSON([]);
        }

        $search = $this->request->getGet('q') ?? $this->request->getPost('q');
        if (empty($search)) {
            return $this->response->setJSON([]);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('product');
        $builder->select('product.id, product.product_name as text, product.main_img');
        $builder->like('product.product_name', $search);
        $builder->where('product.is_delete', 0);
        $builder->where('product.status', 1);
        $builder->limit(20);

        return $this->response->setJSON($builder->get()->getResultArray());
    }
}
