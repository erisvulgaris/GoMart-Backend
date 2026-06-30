<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CartsModel;
use App\Models\CategoryModel;
use App\Models\OrderProductModel;
use App\Models\ProductImagesModel;
use App\Models\ProductModel;
use App\Models\ProductRatingsModel;
use App\Models\ProductVariantsModel;
use App\Models\SellerModel;
use App\Models\SubcategoryModel;
use App\Models\UserModel;
use App\Models\ProductSortTypeModel;
use App\Models\ProductSubcategoryModel;


class Product extends BaseController
{
    public function index()
    {
        $data['settings'] = $this->settings;

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

        // Redirect to loader if no city is selected
        if (session()->get('city_id') == null) {
            $data['session_load'] = 0;
            return view('website/loader', $data);
        }


        $categoryModel = new CategoryModel();
        $data['categorys'] = $this->settings['frontend_category_section'] == 1 ? $categoryModel->orderBy('row_order', 'asc')->findAll() : [];

        $brandModel = new BrandModel();
        $data['brands'] = $this->settings['frontend_brand_section'] == 1 ? $brandModel->orderBy('row_order', 'asc')->findAll() : [];

        $sellerModel = new SellerModel();
        $data['sellers'] = $this->settings['frontend_seller_section'] == 1 ? $sellerModel->where('city_id', session()->get('city_id'))->findAll() : [];

        $productSortTypeModel = new ProductSortTypeModel();
        $data['productSorts'] = $productSortTypeModel->findAll();

        $data['is_mobile'] = preg_match('/(iphone|ipod|android|blackberry|mobile|tablet|kindle|mobi|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

        return view('website/product/product', $data);
    }

    public function getPopularProductWithVariants()
    {
        $data['settings'] = $this->settings;
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

        // Redirect to loader if no city is selected
        if (session()->get('city_id') == null) {
            $data['session_load'] = 0;
            return view('website/loader', $data);
        }

        $categoryModel = new CategoryModel();
        $data['categorys'] = $this->settings['frontend_category_section'] == 1 ? $categoryModel->orderBy('row_order', 'asc')->findAll() : [];

        $brandModel = new BrandModel();
        $data['brands'] = $this->settings['frontend_brand_section'] == 1 ? $brandModel->orderBy('row_order', 'asc')->findAll() : [];

        $sellerModel = new SellerModel();
        $data['sellers'] = $this->settings['frontend_seller_section'] == 1 ? $sellerModel->where('city_id', session()->get('city_id'))->findAll() : [];

        $productSortTypeModel = new ProductSortTypeModel();
        $data['productSorts'] = $productSortTypeModel->findAll();

        $data['is_mobile'] = preg_match('/(iphone|ipod|android|blackberry|mobile|tablet|kindle|mobi|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

        $data['is_popular'] = true;
        return view('website/product/product', $data);
    }

    public function getDealoftheDayProductWithVariants()
    {
        $data['settings'] = $this->settings;
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

        // Redirect to loader if no city is selected
        if (session()->get('city_id') == null) {
            $data['session_load'] = 0;
            return view('website/loader', $data);
        }

        $categoryModel = new CategoryModel();
        $data['categorys'] = $this->settings['frontend_category_section'] == 1 ? $categoryModel->orderBy('row_order', 'asc')->findAll() : [];

        $brandModel = new BrandModel();
        $data['brands'] = $this->settings['frontend_brand_section'] == 1 ? $brandModel->orderBy('row_order', 'asc')->findAll() : [];

        $sellerModel = new SellerModel();
        $data['sellers'] = $this->settings['frontend_seller_section'] == 1 ? $sellerModel->where('city_id', session()->get('city_id'))->findAll() : [];

        $productSortTypeModel = new ProductSortTypeModel();
        $data['productSorts'] = $productSortTypeModel->findAll();

        $data['is_mobile'] = preg_match('/(iphone|ipod|android|blackberry|mobile|tablet|kindle|mobi|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

        $data['is_dealoftheday'] = true;
        return view('website/product/product', $data);
    }

    public function getBrandProductList($brand_slug)
    {
        $data['settings'] = $this->settings;
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

        // Redirect to loader if no city is selected
        if (session()->get('city_id') == null) {
            $data['session_load'] = 0;
            return view('website/loader', $data);
        }

        $categoryModel = new CategoryModel();
        $data['categorys'] = $this->settings['frontend_category_section'] == 1 ? $categoryModel->orderBy('row_order', 'asc')->findAll() : [];

        $brandModel = new BrandModel();
        $data['brands'] =  $brandModel->orderBy('row_order', 'asc')->findAll();

        $sellerModel = new SellerModel();
        $data['sellers'] = $this->settings['frontend_seller_section'] == 1 ? $sellerModel->where('city_id', session()->get('city_id'))->findAll() : [];

        $productSortTypeModel = new ProductSortTypeModel();
        $data['productSorts'] = $productSortTypeModel->findAll();

        $data['is_mobile'] = preg_match('/(iphone|ipod|android|blackberry|mobile|tablet|kindle|mobi|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

        $data['is_brand'] = true;
        $data['brand_slug'] = $brand_slug;
        return view('website/product/product', $data);
    }

    public function getSellerProductList($seller_slug)
    {
        $data['settings'] = $this->settings;
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

        // Redirect to loader if no city is selected
        if (session()->get('city_id') == null) {
            $data['session_load'] = 0; 
            return view('website/loader', $data);
        }

        $categoryModel = new CategoryModel();
        $data['categorys'] = $this->settings['frontend_category_section'] == 1 ? $categoryModel->orderBy('row_order', 'asc')->findAll() : [];

        $brandModel = new BrandModel();
        $data['brands'] = $this->settings['frontend_brand_section'] == 1 ? $brandModel->orderBy('row_order', 'asc')->findAll() : [];

        $sellerModel = new SellerModel();
        $data['sellers'] = $this->settings['frontend_seller_section'] == 1 ? $sellerModel->where('city_id', session()->get('city_id'))->findAll() : [];

        $productSortTypeModel = new ProductSortTypeModel();
        $data['productSorts'] = $productSortTypeModel->findAll();

        $data['is_mobile'] = preg_match('/(iphone|ipod|android|blackberry|mobile|tablet|kindle|mobi|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

        $data['is_seller'] = true;
        $data['seller_slug'] = $seller_slug;
        $sellerModel = new \App\Models\SellerModel();
        $data['seller'] = $sellerModel->where('slug', $seller_slug)->where('status', 1)->first() ?? [];
        return view('website/product/product', $data);
    }

    public function fetchProductList()
    {
        $dataInput = $this->request->getJSON(true);

        $settings = $this->settings;
        $country = $this->country;

        $categories = $dataInput['categorys'];
        $brands = $dataInput['brands'];
        $sellers_array = $dataInput['sellers'];
        $fromPrice = (int)$dataInput['fromPrice'];
        $toPrice = (int)$dataInput['toPrice'];

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $productVariantsModel = new ProductVariantsModel();
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $brandModel = new BrandModel();

        $products = []; // Initialize the products array.
        $user = null;

        // Fetch the logged-in user
        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        // Fetch cart item count for the user
        $data['cartItemCount'] = $user
            ? $cartsModel->where('user_id', $user['id'])->countAllResults()
            : 0;

        // Fetch sellers based on the user's city
        $sellerModel = new SellerModel();
        if (empty($sellers_array)) {
            $sellers = $sellerModel->where('city_id', session()->get('city_id'))->findAll();
        } else {
            $sellers = $sellerModel->where('city_id', session()->get('city_id'))->whereIn('slug', $sellers_array)->findAll();
        }

        // Fetch category and brand IDs from their slugs
        $categoryIds = !empty($categories) ? $categoryModel->whereIn('slug', $categories)->findAll() : [];
        $brandIds = !empty($brands) ? $brandModel->whereIn('slug', $brands)->findAll() : [];

        // Extract the IDs of the categories and brands
        $categoryIds = !empty($categoryIds) ? array_column($categoryIds, 'id') : [];
        $brandIds = !empty($brandIds) ? array_column($brandIds, 'id') : [];

        // Get overall min and max prices
        $allVariantsForMinMax = $productVariantsModel
            ->select('price, discounted_price')
            ->where('is_delete', 0)
            ->findAll();

        $minPrice = PHP_INT_MAX;
        $maxPrice = 0;

        foreach ($allVariantsForMinMax as $v) {
            $effectivePrice = ($v['discounted_price'] > 0) ? $v['discounted_price'] : $v['price'];
            if ($effectivePrice < $minPrice) {
                $minPrice = $effectivePrice;
            }
            if ($effectivePrice > $maxPrice) {
                $maxPrice = $effectivePrice;
            }
        }

        // Set actual filter range
        $filterFromPrice = ($fromPrice > 0) ? $fromPrice : $minPrice;
        $filterToPrice = ($toPrice > 0) ? $toPrice : $maxPrice;

        foreach ($sellers as $seller) {
            // Build base product query
            $productQuery = $productModel->select('product.*, COALESCE(ratings.avg_rate, 0) as avg_rating, COALESCE(ratings.rating_count, 0) as rating_count')
                ->join('(SELECT product_id, AVG(rate) as avg_rate, COUNT(*) as rating_count FROM product_ratings WHERE is_active = 1 AND is_delete = 0 GROUP BY product_id) ratings', 'ratings.product_id = product.id', 'left')
                ->where('product.is_delete', 0)
                ->where('product.status', 1)
                ->where('product.seller_id', $seller['id']);

            // Apply category filter via pivot table
            if (!empty($categoryIds)) {
                $productQuery->join('product_categories pc', 'pc.product_id = product.id');
                $productQuery->whereIn('pc.category_id', $categoryIds);
                $productQuery->groupBy('product.id');
            }

            // Apply brand filter if selected
            if (!empty($brandIds)) {
                $productQuery->whereIn('product.brand_id', $brandIds);
            }

            // Determine product sorting based on `productSort` value
            switch ($dataInput['productSort']) {
                case 1:
                    $productQuery->orderBy('product.row_order', 'ASC');
                    $sellerProducts = $productQuery->findAll();
                    break;

                case 2:
                    // Sort by price (Low to High)
                    $sellerProducts = $productQuery->findAll();
                    foreach ($sellerProducts as &$product) {
                        $product['variants'] = $productVariantsModel
                            ->where('product_id', $product['id'])
                            ->where('is_delete', 0)
                            ->findAll();
                    }
                    usort($sellerProducts, function ($a, $b) {
                        $aPrice = isset($a['variants'][0]['discounted_price']) && $a['variants'][0]['discounted_price'] > 0
                            ? $a['variants'][0]['discounted_price']
                            : (isset($a['variants'][0]['price']) ? $a['variants'][0]['price'] : PHP_INT_MAX);

                        $bPrice = isset($b['variants'][0]['discounted_price']) && $b['variants'][0]['discounted_price'] > 0
                            ? $b['variants'][0]['discounted_price']
                            : (isset($b['variants'][0]['price']) ? $b['variants'][0]['price'] : PHP_INT_MAX);

                        return $aPrice <=> $bPrice;
                    });
                    break;

                case 3:
                    // Sort by price (High to Low)
                    $sellerProducts = $productQuery->findAll();
                    foreach ($sellerProducts as &$product) {
                        $product['variants'] = $productVariantsModel
                            ->where('product_id', $product['id'])
                            ->where('is_delete', 0)
                            ->findAll();
                    }
                    usort($sellerProducts, function ($a, $b) {
                        $aPrice = isset($a['variants'][0]['discounted_price']) && $a['variants'][0]['discounted_price'] > 0
                            ? $a['variants'][0]['discounted_price']
                            : (isset($a['variants'][0]['price']) ? $a['variants'][0]['price'] : 0);

                        $bPrice = isset($b['variants'][0]['discounted_price']) && $b['variants'][0]['discounted_price'] > 0
                            ? $b['variants'][0]['discounted_price']
                            : (isset($b['variants'][0]['price']) ? $b['variants'][0]['price'] : 0);

                        return $bPrice <=> $aPrice;
                    });
                    break;

                case 4:
                    // Sort by discount (High to Low)
                    $sellerProducts = $productQuery->findAll();
                    foreach ($sellerProducts as &$product) {
                        $product['variants'] = $productVariantsModel
                            ->where('product_id', $product['id'])
                            ->where('is_delete', 0)
                            ->findAll();
                    }
                    usort($sellerProducts, function ($a, $b) {
                        $aDiscountPercentage = 0;
                        if (isset($a['variants'][0]['price']) && isset($a['variants'][0]['discounted_price']) && $a['variants'][0]['discounted_price'] > 0) {
                            $aDiscountPercentage = (($a['variants'][0]['price'] - $a['variants'][0]['discounted_price']) / $a['variants'][0]['price']) * 100;
                        }

                        $bDiscountPercentage = 0;
                        if (isset($b['variants'][0]['price']) && isset($b['variants'][0]['discounted_price']) && $b['variants'][0]['discounted_price'] > 0) {
                            $bDiscountPercentage = (($b['variants'][0]['price'] - $b['variants'][0]['discounted_price']) / $b['variants'][0]['price']) * 100;
                        }

                        return $bDiscountPercentage <=> $aDiscountPercentage;
                    });
                    break;

                case 5:
                    $sellerProducts = $productQuery->orderBy('product.product_name', 'ASC')->findAll();
                    break;

                case 6:
                    $sellerProducts = $productQuery->where('product.popular', 1)->findAll();
                    break;

                case 7:
                    $sellerProducts = $productQuery->where('product.deal_of_the_day', 1)->findAll();
                    break;

                default:
                    $sellerProducts = $productQuery->findAll();
                    break;
            }

            foreach ($sellerProducts as $product) {
                // Fetch all variants first
                $allVariants = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0)
                    ->findAll();

                if (empty($allVariants)) {
                    continue;
                }

                // Filter variants by price range
                $filteredVariants = [];
                foreach ($allVariants as $variant) {
                    $effectivePrice = ($variant['discounted_price'] > 0)
                        ? $variant['discounted_price']
                        : $variant['price'];

                    if ($effectivePrice >= $filterFromPrice && $effectivePrice <= $filterToPrice) {
                        $filteredVariants[] = $variant;
                    }
                }

                // Skip product if no variants match the price filter
                if (empty($filteredVariants)) {
                    continue;
                }

                // Attach filtered variants
                $product['variants'] = $filteredVariants;

                // Check cart quantity for logged-in user
                $product['cart_quantity'] = 0;
                if (isset($user['id'])) {
                    $cartItem = $cartsModel
                        ->where('user_id', $user['id'])
                        ->where('product_id', $product['id'])
                        ->first();
                    if ($cartItem) {
                        $product['cart_quantity'] = (int) $cartItem['quantity'];
                    }
                }

                $products[] = $product;
            }
        }

        // Return response with filtered products
        return $this->response->setJSON([
            'status' => 'success',
            'products' => $products,
            'minPrice' => (int)$minPrice,
            'maxPrice' => (int)$maxPrice,
            'fromPrice' => (int)$filterFromPrice,
            'toPrice' => (int)$filterToPrice,
            'base_url' => base_url(),
            'currency_symbol' => $country['currency_symbol'],
            'currency_symbol_position' => $settings['currency_symbol_position'],
        ]);
    }

    public function getProductDetails($slug)
    {
        // Load necessary models
        $productModel = new ProductModel();
        $productImagesModel = new ProductImagesModel();
        $productRatingsModel = new ProductRatingsModel();
        $productVariantsModel = new ProductVariantsModel();
        $categoryModel = new CategoryModel();
        $subcategoryModel = new SubcategoryModel();
        $brandModel = new BrandModel();
        $sellerModel = new SellerModel();
        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $user = null;

        // Check for user session
        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (!$user) {
            $data['cartItemCount'] = 0;
        } else {
            $data['cartItemCount'] = $cartsModel->where('user_id', $user['id'])->countAllResults();
            $data['user'] = $user;
        }

        // if (!session()->get('city_id')) {
        //     return view('website/loader', $data);
        // }

        // Fetch product details based on slug
        $product = $productModel->where('slug', $slug)
            ->where('status', 1)->where('is_delete', 0)->first();

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Initialize images array as empty
        $product['images'] = [];

        // Fetch product variants first to get the first variant
        $product['variants'] = $productVariantsModel->where('product_id', $product['id'])->where('is_delete', 0)->findAll();

        // Get the first variant's ID if variants exist
        $firstVariantId = !empty($product['variants']) ? $product['variants'][0]['id'] : null;

        // Fetch ALL images for the first variant
        if ($firstVariantId) {
            $firstVariantImages = $productImagesModel
                ->where('product_id', $product['id'])
                ->where('product_variant_id', $firstVariantId)
                ->findAll();

            if (!empty($firstVariantImages)) {
                $product['images'] = array_merge($product['images'], $firstVariantImages);
            }
        }

        // Fetch additional general product images AFTER variant images
        $additionalImages = $productImagesModel
            ->where('product_id', $product['id'])
            ->where('product_variant_id', 0)
            ->findAll();

        // Add general images after variant images
        $product['images'] = array_merge($product['images'], $additionalImages);

        // If no images at all, fallback to main_img as last resort
        if (empty($product['images'])) {
            $product['images'] = [['id' => null, 'image' => $product['main_img']]];
        }

        // For each variant, check if it is in the user's cart and set cart quantity
        // Also attach variant images to each variant
        foreach ($product['variants'] as &$variant) {
            $cartItem = $user ? $cartsModel
                ->where('user_id', $user['id'])
                ->where('product_variant_id', $variant['id'])
                ->first() : null;

            $variant['cart_quantity'] = $cartItem ? $cartItem['quantity'] : 0;

            // Fetch ALL variant-specific images
            $variantImages = $productImagesModel
                ->where('product_id', $product['id'])
                ->where('product_variant_id', $variant['id'])
                ->findAll();

            // If variant has images, use the first one as primary
            if (!empty($variantImages)) {
                $variant['image'] = $variantImages[0]['image'];
                $variant['images'] = $variantImages;
            } else {
                // Fallback to general product images if variant has no specific images
                $generalImages = $productImagesModel
                    ->where('product_id', $product['id'])
                    ->where('product_variant_id', 0)
                    ->findAll();

                $variant['image'] = !empty($generalImages) ? $generalImages[0]['image'] : $product['main_img'];
                $variant['images'] = $generalImages;
            }
        }

        // Fetch product ratings and calculate average rating
        $ratings = $productRatingsModel->where('product_id', $product['id'])->where('is_approved_to_show', 1)->where('is_active', 1)->where('is_delete', 0)->findAll();
        $totalRatingCount = count($ratings);
        $averageRating = $totalRatingCount > 0 ? array_sum(array_column($ratings, 'rate')) / $totalRatingCount : 0;
        $product['average_rating'] = round($averageRating, 1);
        $product['rating_count'] = $totalRatingCount;

        // Count ratings by star value (1 to 5)
        $product['star_ratings'] = [
            '1_star' => $productRatingsModel->where('product_id', $product['id'])->where('rate', 1)->where('is_approved_to_show', 1)->where('is_active', 1)->where('is_delete', 0)->countAllResults(),
            '2_star' => $productRatingsModel->where('product_id', $product['id'])->where('rate', 2)->where('is_approved_to_show', 1)->where('is_active', 1)->where('is_delete', 0)->countAllResults(),
            '3_star' => $productRatingsModel->where('product_id', $product['id'])->where('rate', 3)->where('is_approved_to_show', 1)->where('is_active', 1)->where('is_delete', 0)->countAllResults(),
            '4_star' => $productRatingsModel->where('product_id', $product['id'])->where('rate', 4)->where('is_approved_to_show', 1)->where('is_active', 1)->where('is_delete', 0)->countAllResults(),
            '5_star' => $productRatingsModel->where('product_id', $product['id'])->where('rate', 5)->where('is_approved_to_show', 1)->where('is_active', 1)->where('is_delete', 0)->countAllResults(),
        ];

        // Fetch category and subcategory details via pivot tables
        $productCategoryModel = new \App\Models\ProductCategoryModel();
        $productSubcategoryModel = new \App\Models\ProductSubcategoryModel();

        $productCategoryRows = $productCategoryModel->where('product_id', $product['id'])->findAll();
        $productSubcategoryRows = $productSubcategoryModel->where('product_id', $product['id'])->findAll();

        $linkedCategoryIds = array_column($productCategoryRows, 'category_id');
        $linkedSubcategoryIds = array_column($productSubcategoryRows, 'subcategory_id');

        $product['category'] = !empty($linkedCategoryIds) ? $categoryModel->find($linkedCategoryIds[0]) : null;
        $product['subcategory'] = !empty($linkedSubcategoryIds) ? $subcategoryModel->find($linkedSubcategoryIds[0]) : null;

        // Fetch brand details based on brand_id
        $product['brand'] = $brandModel->find($product['brand_id']);

        // Fetch seller details based on seller_id
        $product['seller'] = $sellerModel->select('store_name, store_address, map_address, latitude, longitude')->find($product['seller_id']);

        // Pass data to the view
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;
        $data['product'] = $product;

        $data['similarProducts'] = !empty($linkedSubcategoryIds)
            ? $productModel->similarProducts($linkedSubcategoryIds[0])
            : [];
        $data['categoryProducts'] = !empty($linkedCategoryIds)
            ? $productModel->categoryProducts($linkedCategoryIds)
            : [];

        $productRatingsModel = new ProductRatingsModel();

        $data['productRatings'] = $productRatingsModel
            ->select('product_ratings.id, product_ratings.user_id, product_ratings.rate, product_ratings.title, product_ratings.review, product_ratings.created_at, user.name, user.login_type, user.img')
            ->join('user', 'user.id = product_ratings.user_id', 'left')
            ->where([
                'product_ratings.is_approved_to_show' => 1,
                'product_ratings.is_active' => 1,
                'product_ratings.is_delete' => 0,
                'product_ratings.product_id' => $product['id'], // Ensure $productId is sanitized
            ])
            ->findAll();


        if ($user) {
            // Logged-in user: fetch their review and the latest 3 additional reviews
            $loggedInUserRating = $productRatingsModel
                ->select('product_ratings.id, product_ratings.user_id, product_ratings.rate, product_ratings.title, product_ratings.review, product_ratings.created_at, user.name, user.login_type, user.img')
                ->join('user', 'user.id = product_ratings.user_id', 'left')
                ->where([
                    'product_ratings.is_approved_to_show' => 1,
                    'product_ratings.is_active' => 1,
                    'product_ratings.is_delete' => 0,
                    'product_ratings.product_id' => $product['id'],
                    'product_ratings.user_id' => $user['id'],
                ])
                ->first(); // Fetch only the logged-in user's review

            $latestRatings = $productRatingsModel
                ->select('product_ratings.id, product_ratings.user_id, product_ratings.rate, product_ratings.title, product_ratings.review, product_ratings.created_at, user.name, user.login_type, user.img')
                ->join('user', 'user.id = product_ratings.user_id', 'left')
                ->where([
                    'product_ratings.is_approved_to_show' => 1,
                    'product_ratings.is_active' => 1,
                    'product_ratings.is_delete' => 0,
                    'product_ratings.product_id' => $product['id'],
                ])
                ->where('product_ratings.user_id !=', $user['id']) // Exclude the logged-in user's review
                ->orderBy('product_ratings.created_at', 'DESC')
                ->limit(3) // Limit to the latest 3 reviews
                ->findAll();

            $data['productRatings'] = array_filter(array_merge([$loggedInUserRating], $latestRatings));
        } else {
            // Not logged-in: fetch only the latest 3 reviews
            $data['productRatings'] = $productRatingsModel
                ->select('product_ratings.id, product_ratings.user_id, product_ratings.rate, product_ratings.title, product_ratings.review, product_ratings.created_at, user.name, user.login_type, user.img')
                ->join('user', 'user.id = product_ratings.user_id', 'left')
                ->where([
                    'product_ratings.is_approved_to_show' => 1,
                    'product_ratings.is_active' => 1,
                    'product_ratings.is_delete' => 0,
                    'product_ratings.product_id' => $product['id'],
                ])
                ->orderBy('product_ratings.created_at', 'DESC')
                ->limit(3) // Limit to the latest 3 reviews
                ->findAll();
        }

        return view('website/product/productDetails', $data);
    }

    public function getProductWithVariants($product_id)
    {
        $productModel = new ProductModel();
        $variantModel = new ProductVariantsModel();

        $settings = $this->settings;
        $country = $this->country;

        // Fetch product details by product ID
        $product = $productModel->where('id', $product_id)
            ->where('status', 1)->first();

        // If product not found, return an error response
        if (!$product) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Product not found'
            ]);
        }

        // Fetch product variants by product ID
        $variants = $variantModel->where('product_id', $product_id)->where('is_delete', 0)->findAll();

        // Return product and variants as JSON
        return $this->response->setJSON([ 
            'status' => 'success',
            'product' => $product,
            'variants' => $variants,
            'currency_symbol' => $country['currency_symbol'],
            'currency_symbol_position' => $settings['currency_symbol_position'],
        ]);
    }

    public function switchVarient()
    {
        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $productImagesModel = new ProductImagesModel();
        $user = null;

        // Check if the user is logged in
        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }
        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        // Default quantity is 0
        $quantity = 0;
        $images = [];

        // Get input data
        $dataInput = $this->request->getJSON(true);
        $productId = $dataInput['productId'] ?? null;
        $variantId = $dataInput['variantId'] ?? null;

        // Check if both product ID and variant ID are provided
        if ($productId && $variantId) {
            // Get product details
            $product = $productModel->find($productId);

            if ($product) {
                // Fetch ALL images for the specific variant
                $variantImages = $productImagesModel
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->findAll();

                // Add variant images first
                foreach ($variantImages as $img) {
                    $images[] = [
                        'id' => $img['id'],
                        'image' => base_url($img['image']),
                        'is_variant_image' => true
                    ];
                }

                // Then fetch general product images (where product_variant_id is 0)
                $additionalImages = $productImagesModel
                    ->where('product_id', $productId)
                    ->where('product_variant_id', 0)
                    ->findAll();

                // Add general images after variant images
                foreach ($additionalImages as $img) {
                    $images[] = [
                        'id' => $img['id'],
                        'image' => base_url($img['image']),
                        'is_variant_image' => false
                    ];
                }

                // Only use main_img as fallback if NO images exist
                if (empty($images)) {
                    $images[] = [
                        'id' => null,
                        'image' => base_url($product['main_img']),
                        'is_fallback' => true
                    ];
                }
            }

            // Check cart quantity if user is logged in
            if ($user) {
                $cartItem = $cartsModel
                    ->where('user_id', $user['id'])
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->first();

                if ($cartItem) {
                    $quantity = $cartItem['quantity'];
                }
            }
        }

        // Return the quantity and images
        return $this->response->setJSON([
            'status' => 'success',
            'quantity' => $quantity,
            'images' => $images,
            'message' => 'Variant data retrieved successfully.',
        ]);
    }

    public function fetchSubcategoryProductList()
    {
        $dataInput = $this->request->getJSON(true);

        $subcategory_slug = $dataInput['subcategory_slug'];

        $settings = $this->settings;
        $country = $this->country;

        $cartsModel = new CartsModel();
        $userModel = new UserModel();
        $productVariantsModel = new ProductVariantsModel();
        $productModel = new ProductModel();
        $subcategoryModel = new SubcategoryModel();

        $products = []; // Initialize the products array.
        $user = null;

        // Fetch the logged-in user
        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        // Fetch cart item count for the user
        $data['cartItemCount'] = $user
            ? $cartsModel->where('user_id', $user['id'])->countAllResults()
            : 0;

        // Fetch sellers based on the user's city
        $sellerModel = new SellerModel();
        $sellers = $sellerModel->where('city_id', session()->get('city_id'))->findAll();

        $subcategory = $subcategoryModel->where('slug', $subcategory_slug)->first();

        // Get product IDs linked to this subcategory via the pivot table
        $productSubcategoryModel = new ProductSubcategoryModel();
        $linkedProductIds = array_column(
            $productSubcategoryModel->where('subcategory_id', $subcategory['id'])->findAll(),
            'product_id'
        );

        if (empty($linkedProductIds)) {
            return $this->response->setJSON([
                'status' => 'success',
                'products' => [],
                'base_url' => base_url(),
                'currency_symbol' => $country['currency_symbol'],
                'currency_symbol_position' => $settings['currency_symbol_position'],
            ]);
        }

        $db = \Config\Database::connect();
        $ratingSubquery = $db->table('product_ratings')
            ->select('product_id, AVG(rate) as avg_rating, COUNT(*) as rating_count')
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->groupBy('product_id')
            ->getCompiledSelect();

        foreach ($sellers as $seller) {
            // Apply sorting and filter logic in a single switch statement
            $productQuery = $productModel->whereIn('id', $linkedProductIds)
                ->where('is_delete', 0)
                ->where('status', 1)
                ->where('seller_id', $seller['id']);

            // Determine product sorting based on `productSort` value
            switch ($dataInput['productSort']) {
                case 1:
                    // Default sorting without additional order
                    $productQuery->orderBy('row_order', 'ASC');
                    $sellerProducts = $productQuery->findAll();
                    break;

                case 2:
                    // Sort by price (Low to High)
                    $sellerProducts = $productQuery->findAll();
                    foreach ($sellerProducts as &$product) {
                        // Fetch product variants
                        $product['variants'] = $productVariantsModel
                            ->where('product_id', $product['id'])
                            ->where('is_delete', 0)
                            ->findAll();
                    }
                    usort($sellerProducts, function ($a, $b) {
                        $aPrice = isset($a['variants'][0]['discounted_price']) && $a['variants'][0]['discounted_price'] > 0
                            ? $a['variants'][0]['discounted_price']
                            : (isset($a['variants'][0]['price']) ? $a['variants'][0]['price'] : PHP_INT_MAX);

                        $bPrice = isset($b['variants'][0]['discounted_price']) && $b['variants'][0]['discounted_price'] > 0
                            ? $b['variants'][0]['discounted_price']
                            : (isset($b['variants'][0]['price']) ? $b['variants'][0]['price'] : PHP_INT_MAX);

                        return $aPrice <=> $bPrice;
                    });
                    break;

                case 3:
                    // Sort by price (High to Low)
                    $sellerProducts = $productQuery->findAll();
                    foreach ($sellerProducts as &$product) {
                        // Fetch product variants
                        $product['variants'] = $productVariantsModel
                            ->where('product_id', $product['id'])
                            ->where('is_delete', 0)
                            ->findAll();
                    }
                    usort($sellerProducts, function ($a, $b) {
                        $aPrice = isset($a['variants'][0]['discounted_price']) && $a['variants'][0]['discounted_price'] > 0
                            ? $a['variants'][0]['discounted_price']
                            : (isset($a['variants'][0]['price']) ? $a['variants'][0]['price'] : -PHP_INT_MAX);

                        $bPrice = isset($b['variants'][0]['discounted_price']) && $b['variants'][0]['discounted_price'] > 0
                            ? $b['variants'][0]['discounted_price']
                            : (isset($b['variants'][0]['price']) ? $b['variants'][0]['price'] : -PHP_INT_MAX);

                        return $bPrice <=> $aPrice;
                    });
                    break;


                case 4:
                    // Sort by discount (High to Low)
                    $sellerProducts = $productQuery->findAll();
                    foreach ($sellerProducts as &$product) {
                        // Fetch product variants
                        $product['variants'] = $productVariantsModel
                            ->where('product_id', $product['id'])
                            ->where('is_delete', 0)
                            ->findAll();
                    }
                    usort($sellerProducts, function ($a, $b) {
                        // Calculate discount percentage for product A
                        if (isset($a['variants'][0]['price']) && isset($a['variants'][0]['discounted_price']) && $a['variants'][0]['discounted_price'] > 0) {
                            $aDiscountPercentage = (($a['variants'][0]['price'] - $a['variants'][0]['discounted_price']) / $a['variants'][0]['price']) * 100;
                        } else {
                            $aDiscountPercentage = 0; // No discount
                        }

                        // Calculate discount percentage for product B
                        if (isset($b['variants'][0]['price']) && isset($b['variants'][0]['discounted_price']) && $b['variants'][0]['discounted_price'] > 0) {
                            $bDiscountPercentage = (($b['variants'][0]['price'] - $b['variants'][0]['discounted_price']) / $b['variants'][0]['price']) * 100;
                        } else {
                            $bDiscountPercentage = 0; // No discount
                        }

                        // Compare discount percentages in descending order (High to Low)
                        return $bDiscountPercentage <=> $aDiscountPercentage;
                    });
                    break;



                case 5:
                    // Sort by product name ascending
                    $sellerProducts = $productQuery->orderBy('product_name', 'ASC')->findAll();
                    break;

                case 6:
                    // Sort by product popular
                    $sellerProducts = $productQuery->where('popular', 1)->findAll();
                    break;

                case 7:
                    // Sort by product deal of the day
                    $sellerProducts = $productQuery->where('deal_of_the_day', 1)->findAll();
                    break;

                // Add other sorting cases if needed
                default:
                    $sellerProducts = $productQuery->findAll();
                    break;
            }

            foreach ($sellerProducts as $product) {
                // 2) Fetch non-deleted variants for this product
                $variants = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0)
                    ->findAll();

                // 3) Only proceed if we actually got at least one variant
                if (empty($variants)) {
                    // no variants → skip this product
                    continue;
                }

                // 4) Attach the variants array
                $product['variants'] = $variants;

                // 5) Default cart_quantity to zero
                $product['cart_quantity'] = 0;

                // 6) If the user is logged in, see if they have this in their cart
                if (isset($user['id'])) {
                    $cartItem = $cartsModel
                        ->where('user_id', $user['id'])
                        ->where('product_id', $product['id'])
                        ->first();
                    if ($cartItem) {
                        $product['cart_quantity'] = (int) $cartItem['quantity'];
                    }
                }

                // 7) Attach rating data
                $ratingRow = $db->query("SELECT AVG(rate) as avg_rating, COUNT(*) as rating_count FROM product_ratings WHERE product_id = ? AND is_active = 1 AND is_delete = 0", [$product['id']])->getRowArray();
                $product['avg_rating'] = $ratingRow ? round((float)$ratingRow['avg_rating'], 1) : 0;
                $product['rating_count'] = $ratingRow ? (int)$ratingRow['rating_count'] : 0;

                // 8) Finally, push into your output array
                $products[] = $product;
            }
        }

        // Return response with sorted products
        return $this->response->setJSON([
            'status' => 'success',
            'products' => $products,
            'base_url' => base_url(),
            'currency_symbol' => $country['currency_symbol'],
            'currency_symbol_position' => $settings['currency_symbol_position'],
        ]);
    }

    public function writeReview()
    {
        date_default_timezone_set($this->timeZone['timezone']);

        $dataInput = $this->request->getJSON(true);
        $user = null;

        $userModel = new UserModel();
        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'To write a review, please log in and ensure you have purchased the item.',
            ]);
        }

        $orderProductModel = new OrderProductModel();
        $orderProducts = $orderProductModel->where('product_id', $dataInput['productId'])->where('user_id', $user['id'])->first();
        if (!$orderProducts) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'To write a review, ensure you have purchased the item.',
            ]);
        }

        $productRatingsModel = new ProductRatingsModel();
        $productRating = $productRatingsModel->where('product_id', $dataInput['productId'])->where('user_id', $user['id'])->first();

        if ($productRating) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You already write review for this product',
            ]);
        }


        $is_approved_to_show = 0;
        if ($this->settings['auto_review_approval']) {
            $is_approved_to_show = 1;
        }


        $productRatingsData = [
            'product_id' => $dataInput['productId'],
            'user_id' => $user['id'],
            'rate' => $dataInput['rate'],
            'title' => $dataInput['title'],
            'review' => $dataInput['review'],
            'created_at' => date('Y-m-d H:i:s'),
            'is_approved_to_show' => $is_approved_to_show,
            'is_active' => 1,
            'is_delete' => 0
        ];

        $productRatings = $productRatingsModel->insert($productRatingsData);

        if ($productRatings) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'You write Review successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unable to write Review']);
        }
    }
}
