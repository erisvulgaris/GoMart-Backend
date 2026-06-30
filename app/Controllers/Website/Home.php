<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\BrandModel;
use App\Models\CartsModel;
use App\Models\FaqsModel;
use App\Models\HomeSectionModel;
use App\Models\ProductVariantsModel;
use App\Models\SellerModel;
use App\Models\UserModel;
use App\Models\SubcategoryModel;
use App\Models\HighlightsModel;
use App\Models\OrderProductModel;
use App\Models\ProductRatingsModel;
use App\Models\HomeScreenModel;
use App\Models\SectionModel;
use App\Models\SectionCategoryModel;
use App\Models\SectionProductModel;
use App\Models\SectionBrandModel;
use App\Models\SectionHighlightModel;
use App\Models\SectionSellerModel;
use App\Models\ProductCategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();

        $user = null;
        // Fetch user details
        $loginType = session()->get('login_type');
        if ($loginType == 'email') {
            $user = $userModel->where('email', session()->get('email'))
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->first();
        } elseif ($loginType == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->first();
        }

        $data['cartItemCount'] = $user ? $cartsModel->where('user_id', $user['id'])->countAllResults() : 0;

        // Redirect to loader if no city is selected
        if (session()->get('city_id') == null || session()->get('deliverable_area_id') == null) {
            $data['session_load'] = 0;
            return view('website/loader', $data);
        }

        $cityId = session()->get('city_id');
        $homeSectionModel = new HomeSectionModel();
        $productVariantsModel = new ProductVariantsModel();
        $sellerModel = new SellerModel();
        $bannerModel = new BannerModel();
        $categoryModel = new CategoryModel();
        $subcategoryModel = new SubcategoryModel();
        $productModel = new ProductModel();
        $brandModel = new BrandModel();

        // Fetch sellers for the selected city
        $sellers = $sellerModel->where('city_id', $cityId)->findColumn('id');
        if ($sellers === null) {
            return view('website/comingSoonCity', $data);
        }

        // Banner arrays initialised here; populated after $defaultScreen is resolved below
        $data['headerBanner'] = [];
        $data['dealOftheDayBanner'] = [];
        $data['homeBanner'] = [];
        $data['footerBanner'] = [];

        $data['categories'] = $this->settings['frontend_category_section']
            ? $categoryModel->orderBy('row_order', 'ASC')->findAll()
            : [];

        foreach ($data['categories'] as $i => $category) {
            $subcategory = $subcategoryModel->where('category_id', $category['id'])
                ->orderBy('row_order', 'ASC')
                ->first();
            $data['categories'][$i]['firstSubcategory'] = $subcategory;
        }



        // Fetch popular products, brands, and deal of the day products
        $data['popularProducts'] = $this->settings['frontend_popular_section'] ? $productModel->getAllPopularProduct((int)$this->settings['popular_product_show_limit'], $this->settings['popular_product_show_sort_by']) : [];
        $data['brands'] = $this->settings['frontend_brand_section'] ? $brandModel->getBrandList() : [];
        $data['dealOfTheDayProducts'] = $this->settings['frontend_deal_of_the_day_section'] ? $productModel->getAllDealOfTheDayProduct((int)$this->settings['deal_of_the_day_product_show_limit'], $this->settings['deal_of_the_day_product_show_sort_by']) : [];
        $data['sellers'] = $this->settings['frontend_seller_section']
            ? $sellerModel->where('city_id', $cityId)->where('is_delete', 0)->where('status', 1)->findAll()
            : [];

        $highlightsModel = new HighlightsModel();
        $sellers1 = $sellerModel
            ->where('city_id', $cityId)
            ->where('is_delete', 0)
            ->where('status', 1)
            ->findAll();

        $sellerIds = array_column($sellers1, 'id');
        $highlightsData = [];
        if (!empty($sellerIds)) {
            $highlights = $highlightsModel
                ->where('is_active', 1)
                ->whereIn('seller_id', $sellerIds)
                ->findAll();
            $sellerSlugs = [];
            foreach ($sellers1 as $seller) {
                $sellerSlugs[$seller['id']] = $seller['slug'];
            }
            foreach ($highlights as $highlight) {
                $sellerId = $highlight['seller_id'];
                $slug = $sellerSlugs[$sellerId] ?? '';

                $highlightsData[] = [
                    'title'       => $highlight['title'],
                    'description' => $highlight['description'],
                    'video'       => $highlight['video'],
                    'image'       => $highlight['image'],
                    'seller_slug' => $slug,
                ];
            }
        }
        $data['highlights'] = $highlightsData;


        // Load bestseller categories
        $productCategoryModel = new ProductCategoryModel();
        $bestsellerCategories = $categoryModel->where('is_bestseller_category', 1)->findAll();
        $bestsellerCategoriesResult = [];

        foreach ($bestsellerCategories as $category) {
            // Fetch product IDs that belong to this category from pivot table
            $productIds = $productCategoryModel
                ->select('product_id')
                ->where('category_id', $category['id'])
                ->findAll();

            $productIdArray = array_column($productIds, 'product_id');

            if (empty($productIdArray)) {
                $bestsellerCategoriesResult[] = [
                    'category_id'   => $category['id'],
                    'category_name' => $category['category_name'],
                    'images'        => [],
                    'total_count'   => 0,
                    'firstSubcategory' => null
                ];
                continue;
            }

            // Get first 4 product images for this category
            $products = $productModel
                ->select('main_img')
                ->whereIn('id', $productIdArray)
                ->where('is_delete', 0)
                ->where('status', 1)
                ->limit(4)
                ->findAll();

            // Total product count (active products only)
            $totalProducts = $productModel
                ->whereIn('id', $productIdArray)
                ->where('is_delete', 0)
                ->where('status', 1)
                ->countAllResults();

            // Format images with base URL
            $productImages = [];
            foreach ($products as $product) {
                $imgPath = $product['main_img'];
                $productImages[] = !empty($imgPath) ? base_url($imgPath) : base_url('assets/images/no-image.png');
            }

            $firstSubcategory = $subcategoryModel
                ->where('category_id', $category['id'])
                ->orderBy('row_order', 'ASC')
                ->first();

            $bestsellerCategoriesResult[] = [
                'category_id'   => $category['id'],
                'category_name' => $category['category_name'],
                'images'        => $productImages,
                'total_count'   => $totalProducts,
                'firstSubcategory' => $firstSubcategory
            ];
        }
        $data['allBestsellerCategory'] = $bestsellerCategoriesResult;


        // Add user details if logged in
        $data['user'] = (session()->has('email') || session()->has('mobile')) ? $user : [];

        

        $data['homeSectionProducts'] = []; // Disabled - using dbSections instead
        // $data['homeSectionProducts'] = $homeSections;

        $homeScreenModel = new HomeScreenModel();

        // Fetch all active home screens (for tabs)
        $allHomeScreens = $homeScreenModel->getActiveHomeScreens();
        $data['allHomeScreens'] = $allHomeScreens;

        // Fetch default home screen
        $defaultScreen = $homeScreenModel->getDefaultScreen();

        // If no default screen, try to get any active screen
        if (empty($defaultScreen)) {
            $defaultScreen = !empty($allHomeScreens) ? $allHomeScreens[0] : null;
        }

        $data['homeScreen'] = $defaultScreen;
        $data['dbSections'] = [];

        if (!empty($defaultScreen)) {
            $data['dbSections'] = $this->buildSectionsForScreen($defaultScreen['id'], $cityId, $sellers, $user, $cartsModel);

            // Fetch banners for this home screen and split by placement
            $screenBanners = $bannerModel->getActiveBannersByHomeScreen($defaultScreen['id']);
            $subcategoryModelBanner = new SubcategoryModel();
            foreach ($screenBanners as $b) {
                $contentId = $b['content_id'] ?? 0;
                $b['firstSubcategory'] = [];
                if ($contentId > 0) {
                    $b['firstSubcategory'] = $subcategoryModelBanner
                        ->where('category_id', $contentId)
                        ->orderBy('row_order', 'ASC')
                        ->first() ?? [];
                }
                $placement = (int)($b['placement'] ?? 0);
                switch ($placement) {
                    case 0: $data['headerBanner'][] = $b; break;
                    case 1: $data['dealOftheDayBanner'][] = $b; break;
                    case 2: $data['homeBanner'][] = $b; break;
                    case 3: $data['footerBanner'][] = $b; break;
                    default: $data['headerBanner'][] = $b; break;
                }
            }
        }
        return view('website/home/home', $data);
    }



    public function getHomeScreenData()
    {
        $homeScreenId = $this->request->getPost('home_screen_id');
        if (empty($homeScreenId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing home_screen_id']);
        }

        // Check city session
        $cityId = session()->get('city_id');
        if (empty($cityId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No city selected']);
        }

        $sellerModel = new SellerModel();
        $sellers = $sellerModel->where('city_id', $cityId)->findColumn('id');
        if ($sellers === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No sellers in city']);
        }

        // Resolve user
        $userModel = new UserModel();
        $cartsModel = new CartsModel();
        $user = null;
        $loginType = session()->get('login_type');
        if ($loginType == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        } elseif ($loginType == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        // Build sections
        $dbSections = $this->buildSectionsForScreen($homeScreenId, $cityId, $sellers, $user, $cartsModel);

        // Build banners
        $bannerModel = new BannerModel();
        $subcategoryModel = new SubcategoryModel();
        $screenBanners = $bannerModel->getActiveBannersByHomeScreen($homeScreenId);
        $headerBanner = [];
        $footerBanner = [];
        foreach ($screenBanners as $b) {
            $contentId = $b['content_id'] ?? 0;
            $b['firstSubcategory'] = [];
            if ($contentId > 0) {
                $b['firstSubcategory'] = $subcategoryModel
                    ->where('category_id', $contentId)
                    ->orderBy('row_order', 'ASC')
                    ->first() ?? [];
            }
            $placement = (int)($b['placement'] ?? 0);
            if ($placement == 0) {
                $headerBanner[] = $b;
            } elseif ($placement == 3) {
                $footerBanner[] = $b;
            }
        }

        // Render partial view
        $data = [
            'settings' => $this->settings,
            'country'  => $this->country,
            'dbSections' => $dbSections,
            'headerBanner' => $headerBanner,
            'footerBanner' => $footerBanner,
            'user' => $user ?? [],
        ];

        $html = view('website/home/home_screen_content', $data);

        return $this->response->setJSON([
            'status' => 'success',
            'html'   => $html,
        ]);
    }


    private function buildSectionsForScreen($homeScreenId, $cityId, $sellers, $user, $cartsModel)
    {
        $sectionModel = new SectionModel();
        $sectionCategoryModel = new SectionCategoryModel();
        $sectionProductModel = new SectionProductModel();
        $sectionBrandModel = new SectionBrandModel();
        $sectionHighlightModel = new SectionHighlightModel();
        $sectionSellerModel = new SectionSellerModel();
        $categoryModel = new CategoryModel();
        $subcategoryModel = new SubcategoryModel();
        $productModel = new ProductModel();
        $brandModel = new BrandModel();
        $sellerModel = new SellerModel();
        $highlightsModel = new HighlightsModel();
        $productVariantsModel = new ProductVariantsModel();
        $productCategoryModel = new ProductCategoryModel();

        $sections = $sectionModel->getActiveSectionsByHomeScreen($homeScreenId);

        // Get sellers data for highlights
        $sellers1 = $sellerModel->where('city_id', $cityId)->where('is_delete', 0)->where('status', 1)->findAll();

        $dbSections = [];

        foreach ($sections as $section) {
            $sectionStyle = $section['section_style'];
            $sectionContent = [];

            switch ($sectionStyle) {
                case 'category_list':
                    $linkedCategories = $sectionCategoryModel->getCategoriesBySectionId($section['id']);
                    $categoryIds = array_column($linkedCategories, 'category_id');
                    if (!empty($categoryIds)) {
                        $categories = $categoryModel->whereIn('id', $categoryIds)->findAll();
                        $orderedCategories = [];
                        foreach ($categoryIds as $catId) {
                            foreach ($categories as $cat) {
                                if ($cat['id'] == $catId) { $orderedCategories[] = $cat; break; }
                            }
                        }
                        $sectionContent = $orderedCategories;
                    } else {
                        $sectionContent = $categoryModel->orderBy('row_order', 'ASC')->findAll();
                    }
                    foreach ($sectionContent as $i => $cat) {
                        $firstSub = $subcategoryModel->where('category_id', $cat['id'])->orderBy('row_order', 'ASC')->first();
                        $sectionContent[$i]['firstSubcategory'] = $firstSub;
                    }
                    break;

                case 'best_seller':
                    $linkedCategories = $sectionCategoryModel->getCategoriesBySectionId($section['id']);
                    $categoryIds = array_column($linkedCategories, 'category_id');
                    if (!empty($categoryIds)) {
                        $bestsellerCategories = $categoryModel->whereIn('id', $categoryIds)->where('is_bestseller_category', 1)->findAll();
                    } else {
                        $bestsellerCategories = $categoryModel->where('is_bestseller_category', 1)->findAll();
                    }
                    foreach ($bestsellerCategories as $category) {
                        $catProductIds = $productCategoryModel->select('product_id')->where('category_id', $category['id'])->findAll();
                        $catProductIdArray = array_column($catProductIds, 'product_id');
                        if (empty($catProductIdArray)) continue;
                        $products = $productModel->select('main_img')->whereIn('id', $catProductIdArray)->where('is_delete', 0)->where('status', 1)->limit(4)->find();
                        $totalProducts = $productModel->whereIn('id', $catProductIdArray)->where('is_delete', 0)->where('status', 1)->countAllResults();
                        $productImages = [];
                        foreach ($products as $product) {
                            $imgPath = $product['main_img'];
                            $productImages[] = !empty($imgPath) ? base_url($imgPath) : base_url('assets/images/no-image.png');
                        }
                        $firstSubcategory = $subcategoryModel->where('category_id', $category['id'])->orderBy('row_order', 'ASC')->first();
                        $sectionContent[] = [
                            'category_id'   => $category['id'],
                            'category_name' => $category['category_name'],
                            'category_img'  => $category['category_img'],
                            'images'        => $productImages,
                            'total_count'   => $totalProducts,
                            'firstSubcategory' => $firstSubcategory
                        ];
                    }
                    break;

                case 'product_list':
                    $linkedProducts = $sectionProductModel->getProductsBySectionId($section['id']);
                    $productIds = array_column($linkedProducts, 'product_id');
                    if (!empty($productIds)) {
                        $db = \Config\Database::connect();
                        $builder = $db->table('product p');
                        $builder->select('p.*,
                            MIN(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as min_price,
                            MAX(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as max_price,
                            COALESCE(sales.total_sold, 0) as total_sales,
                            COALESCE(ratings.avg_rate, 0) as avg_rating,
                            COALESCE(ratings.rating_count, 0) as rating_count')
                            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'left')
                            ->join('(SELECT product_id, SUM(quantity) as total_sold FROM order_products GROUP BY product_id) sales', 'sales.product_id = p.id', 'left')
                            ->join('(SELECT product_id, AVG(rate) as avg_rate, COUNT(*) as rating_count FROM product_ratings WHERE is_active = 1 AND is_delete = 0 GROUP BY product_id) ratings', 'ratings.product_id = p.id', 'left')
                            ->whereIn('p.id', $productIds)
                            ->where('p.is_delete', 0)
                            ->where('p.status', 1)
                            ->groupBy('p.id')
                            ->orderBy('FIELD(p.id, ' . implode(',', $productIds) . ')');
                        $sectionProducts = $builder->get()->getResultArray();
                    } else {
                        $db = \Config\Database::connect();
                        $builder = $db->table('product p');
                        $builder->select('p.*,
                            MIN(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as min_price,
                            MAX(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as max_price,
                            COALESCE(sales.total_sold, 0) as total_sales,
                            COALESCE(ratings.avg_rate, 0) as avg_rating,
                            COALESCE(ratings.rating_count, 0) as rating_count')
                            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'left')
                            ->join('(SELECT product_id, SUM(quantity) as total_sold FROM order_products GROUP BY product_id) sales', 'sales.product_id = p.id', 'left')
                            ->join('(SELECT product_id, AVG(rate) as avg_rate, COUNT(*) as rating_count FROM product_ratings WHERE is_active = 1 AND is_delete = 0 GROUP BY product_id) ratings', 'ratings.product_id = p.id', 'left')
                            ->where('p.is_delete', 0)
                            ->where('p.status', 1);
                        if (!empty($section['category_id'])) {
                            $builder->join('product_categories pc', 'pc.product_id = p.id', 'inner');
                            $builder->where('pc.category_id', $section['category_id']);
                        }
                        if (!empty($section['sub_category_id'])) {
                            $builder->join('product_subcategories psc', 'psc.product_id = p.id', 'inner');
                            $builder->where('psc.subcategory_id', $section['sub_category_id']);
                        }
                        if (!empty($section['brand_id'])) { $builder->where('p.brand_id', $section['brand_id']); }
                        if (!empty($section['seller_id'])) { $builder->where('p.seller_id', $section['seller_id']); }
                        if (!empty($sellers)) { $builder->whereIn('p.seller_id', $sellers); }
                        $sortBy = $section['sort_by'] ?? 'default';
                        switch ($sortBy) {
                            case 'alphabetical': $builder->orderBy('p.product_name', 'ASC'); break;
                            case 'low_to_high': $builder->orderBy('min_price', 'ASC'); break;
                            case 'high_to_low': $builder->orderBy('max_price', 'DESC'); break;
                            case 'best_selling': $builder->orderBy('total_sales', 'DESC'); break;
                            case 'best_rated': $builder->orderBy('avg_rating', 'DESC'); break;
                            default: $builder->orderBy('p.id', 'DESC');
                        }
                        $limit = $section['no_of_content'] ?? 20;
                        $builder->limit($limit);
                        $builder->groupBy('p.id');
                        $sectionProducts = $builder->get()->getResultArray();
                    }
                    if (!empty($sectionProducts)) {
                        $prodIds = array_column($sectionProducts, 'id');
                        $allVariants = $productVariantsModel->whereIn('product_id', $prodIds)->where('is_delete', 0)->findAll();
                        $variantsByProduct = [];
                        foreach ($allVariants as $v) { $variantsByProduct[$v['product_id']][] = $v; }
                        $cartData = [];
                        if ($user) {
                            $cartItems = $cartsModel->select('product_id, quantity')->where('user_id', $user['id'])->whereIn('product_id', $prodIds)->findAll();
                            foreach ($cartItems as $item) { $cartData[$item['product_id']] = (int)$item['quantity']; }
                        }
                        $validProducts = [];
                        foreach ($sectionProducts as $k => $product) {
                            $variants = $variantsByProduct[$product['id']] ?? [];
                            if (empty($variants)) continue;
                            usort($variants, function ($a, $b) {
                                $aPrice = ($a['discounted_price'] > 0) ? $a['discounted_price'] : $a['price'];
                                $bPrice = ($b['discounted_price'] > 0) ? $b['discounted_price'] : $b['price'];
                                return $aPrice <=> $bPrice;
                            });
                            $sectionProducts[$k]['variants'] = $variants;
                            $sectionProducts[$k]['cart_quantity'] = $cartData[$product['id']] ?? 0;
                            $validProducts[] = $sectionProducts[$k];
                        }
                        $sectionContent = $validProducts;
                    }
                    break;

                case 'highlight':
                    $linkedHighlights = $sectionHighlightModel->getHighlightsBySectionId($section['id']);
                    $highlightIds = array_column($linkedHighlights, 'highlight_id');
                    if (!empty($highlightIds)) {
                        $highlights = $highlightsModel->whereIn('id', $highlightIds)->findAll();
                    } else {
                        $highlights = $highlightsModel->where('is_active', 1)->findAll();
                    }
                    $sellerSlugs = [];
                    foreach ($sellers1 as $seller) { $sellerSlugs[$seller['id']] = $seller['slug']; }
                    foreach ($highlights as $hl) {
                        $sectionContent[] = [
                            'title'       => $hl['title'],
                            'description' => $hl['description'],
                            'video'       => $hl['video'],
                            'image'       => $hl['image'],
                            'seller_slug' => $sellerSlugs[$hl['seller_id']] ?? ''
                        ];
                    }
                    break;

                case 'shop_by_brand':
                    $linkedBrands = $sectionBrandModel->getBrandsBySectionId($section['id']);
                    $brandIds = array_column($linkedBrands, 'brand_id');
                    if (!empty($brandIds)) {
                        $brands = $brandModel->whereIn('id', $brandIds)->findAll();
                    } else {
                        $brands = $brandModel->getBrandList();
                    }
                    $sectionContent = $brands;
                    break;

                case 'shop_by_seller':
                    $linkedSellers = $sectionSellerModel->getSellersBySectionId($section['id']);
                    $sellerIds = array_column($linkedSellers, 'seller_id');
                    if (!empty($sellerIds)) {
                        $sellersData = $sellerModel->whereIn('id', $sellerIds)->where('is_delete', 0)->where('status', 1)->findAll();
                    } else {
                        $sellersData = $sellerModel->where('city_id', $cityId)->where('is_delete', 0)->where('status', 1)->findAll();
                    }
                    $sectionContent = $sellersData;
                    break;
            }

            $dbSections[] = [
                'id'             => $section['id'],
                'home_screen_id' => $section['home_screen_id'],
                'title'          => $section['title'],
                'section_style'  => $sectionStyle,
                'content'        => $sectionContent,
                'sort_order'     => $section['sort_order']
            ];
        }

        return $dbSections;
    }

    public function contactUs()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        return view('website/home/contactUs', $data);
    }

    public function faq()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $faqsModel = new FaqsModel();

        $data['faqs'] = $faqsModel->where('status', 1)->orderBy('row_order', 'asc')->findAll();

        return view('website/home/faq', $data);
    }

    public function aboutUs()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $data['aboutUs'] = $this->settings['customer_app_about'];


        return view('website/home/aboutUs', $data);
    }

    public function privacyPolicy()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $data['privacyPolicy'] = $this->settings['customer_app_privacy_policy'];

        return view('website/home/privacyPolicy', $data);
    }

    public function termsCondition()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $data['termsCondition'] = $this->settings['customer_app_terms_policy'];

        return view('website/home/termsCondition', $data);
    }

    public function refundPolicy()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }
        $data['refundPolicy'] = $this->settings['customer_app_refund_policy'];


        return view('website/home/refundPolicy', $data);
    }

    public function noProductAvilable()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }


        return view('website/category/noProductAvilable', $data);
    }

    public function privacyPolicyDelivery()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;
        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $data['privacyPolicy'] = $this->settings['delivery_app_privacy_policy'];

        return view('website/home/privacyPolicy', $data);
    }

    public function termsConditionDelivery()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $data['termsCondition'] = $this->settings['delivery_app_terms_policy'];

        return view('website/home/termsCondition', $data);
    }

    public function aboutUsDelivery()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $cartItemCount = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['cartItemCount'] = $cartItemCount;
            $data['user'] = $user;
        }

        $data['aboutUs'] = $this->settings['delivery_app_about'];


        return view('website/home/aboutUs', $data);
    }
}
