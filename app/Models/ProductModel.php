<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'product';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_name', 'brand_id', 'seller_id', 'row_order', 'description', 'status', 'main_img', 'date', 'popular', 'deal_of_the_day', 'is_delete', 'slug', 'manufacturer', 'made_in', 'is_returnable', 'return_days', 'total_allowed_quantity', 'tax_included_in_price', 'fssai_lic_no', 'seo_title', 'seo_keywords', 'seo_alt_text', 'seo_description', 'added_by_seller'];

    public function insertProduct($data)
    {
        return $this->insert($data);
    }

    // Function to get products with category and subcategory details
    public function getProductsList($category, $seller, $status, $stock)
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);

        $builder->select("
            product.*,
            seller.store_name,
            brand.brand,
            GROUP_CONCAT(DISTINCT category.category_name SEPARATOR ', ') AS categories,
            GROUP_CONCAT(DISTINCT subcategory.name SEPARATOR ', ') AS subcategories
        ");

        // Join seller
        $builder->join('seller', 'seller.id = product.seller_id', 'left');
        $builder->join('brand', 'brand.id = product.brand_id', 'left');

        // Join product_categories → categories
        $builder->join('product_categories pc', 'pc.product_id = product.id', 'left');
        $builder->join('category', 'category.id = pc.category_id', 'left');

        // Join product_subcategories → subcategories
        $builder->join('product_subcategories psc', 'psc.product_id = product.id', 'left');
        $builder->join('subcategory', 'subcategory.id = psc.subcategory_id', 'left');

        $builder->where('product.is_delete', 0);

        // Filter by seller
        if (!empty($seller)) {
            $builder->where('product.seller_id', $seller);
        }

        // Filter by category (array)
        if (!empty($category)) {
            if (is_array($category)) {
                $builder->whereIn('pc.category_id', $category);
            } else {
                $builder->where('pc.category_id', $category);
            }
        }

        // Filter by product status
        if ($status === "0" || $status === "1" || $status === 0 || $status === 1) {
            $builder->where('product.status', $status);
        }

        $builder->groupBy('product.id');
        $builder->orderBy('product.id', 'DESC');

        $products = $builder->get()->getResultArray();
        // Fetch and add variations for each product
        $productVariantsModel = new ProductVariantsModel();
        foreach ($products as &$product) {
            if ($stock == 1) {
                // Fetch products with stock > 0 or unlimited stock
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->groupStart()
                    ->where('stock >', 0)
                    ->orWhere('is_unlimited_stock', 1)
                    ->groupEnd()
                    ->findAll();
            } elseif ($stock === "0") {
                // Fetch products with stock equal to 0
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->groupStart()
                    ->where('stock', 0)
                    ->Where('is_unlimited_stock', 0)
                    ->groupEnd()
                    ->findAll();
            } elseif ($stock == 2) {
                // Fetch all active variants without stock filtering
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->groupStart()
                    ->where('stock <', 50)
                    ->Where('is_unlimited_stock', 0)
                    ->groupEnd()
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->orderBy('stock', 'desc')
                    ->findAll();
            } else {
                // Fetch all active variants without stock filtering
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->findAll();
            }
        }

        return $products;
    }
    public function getRequestProductsList($category, $seller,  $status, $stock)
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('product.*, category.category_name, subcategory.name as subcategory_name, seller.store_name, brand.brand');
        $builder->join('category', 'category.id = product.category_id', 'left');
        $builder->join('seller', 'seller.id = product.seller_id', 'left');
        $builder->join('brand', 'brand.id = product.brand_id', 'left');
        $builder->join('subcategory', 'subcategory.id = product.subcategory_id', 'left');
        $builder->where('product.is_delete', 0);
        $builder->where('product.added_by_seller', 1);

        if ($seller) {
            $builder->where('product.seller_id', $seller);
        }
        if ($category) {
            $builder->where('product.category_id', $category);
        }
        if ($status == 0 || $status == 1) {
            $builder->where('product.status', $status);
        }
        $builder->orderBy('product.id', 'DESC');

        $products = $builder->get()->getResultArray();
        // Fetch and add variations for each product
        $productVariantsModel = new ProductVariantsModel();
        foreach ($products as &$product) {
            if ($stock == 1) {
                // Fetch products with stock > 0 or unlimited stock
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->groupStart()
                    ->where('stock >', 0)
                    ->orWhere('is_unlimited_stock', 1)
                    ->groupEnd()
                    ->findAll();
            } elseif ($stock === "0") {
                // Fetch products with stock equal to 0
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->groupStart()
                    ->where('stock', 0)
                    ->Where('is_unlimited_stock', 0)
                    ->groupEnd()
                    ->findAll();
            } elseif ($stock == 2) {
                // Fetch all active variants without stock filtering
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->groupStart()
                    ->where('stock <', 50)
                    ->Where('is_unlimited_stock', 0)
                    ->groupEnd()
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->orderBy('stock', 'desc')
                    ->findAll();
            } else {
                // Fetch all active variants without stock filtering
                $product['variants'] = $productVariantsModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0) // Only non-deleted variants
                    ->findAll();
            }
        }

        return $products;
    }

    public function getProductsWithDetails()
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('product.*, category.category_name, subcategory.name as subcategory_name, seller.store_name, brand.brand');
        $builder->join('category', 'category.id = product.category_id', 'left');
        $builder->join('seller', 'seller.id = product.seller_id', 'left');
        $builder->join('brand', 'brand.id = product.brand_id', 'left');
        $builder->join('subcategory', 'subcategory.id = product.subcategory_id', 'left');
        $builder->where('product.is_delete', 0);
        $builder->orderBy('product.id', 'ASC');

        $products = $builder->get()->getResultArray();
        // Fetch and add variations for each product
        $productVariantsModel = new ProductVariantsModel();
        foreach ($products as &$product) {
            $product['variants'] = $productVariantsModel
                ->where('product_id', $product['id'])
                ->where('is_delete', 0)  // Fetch only active variants
                ->findAll();
        }

        return $products;
    }
    public function getProductsWithDetailsForSeller($seller_id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('product.*, category.category_name, subcategory.name as subcategory_name, seller.store_name, brand.brand');
        $builder->join('category', 'category.id = product.category_id', 'left');
        $builder->join('seller', 'seller.id = product.seller_id', 'left');
        $builder->join('brand', 'brand.id = product.brand_id', 'left');
        $builder->join('subcategory', 'subcategory.id = product.subcategory_id', 'left');
        $builder->where('product.is_delete', 0);
        $builder->where('product.seller_id', $seller_id);
        $builder->orderBy('product.id', 'ASC');

        $products = $builder->get()->getResultArray();
        // Fetch and add variations for each product
        $productVariantsModel = new ProductVariantsModel();
        foreach ($products as &$product) {
            $product['variants'] = $productVariantsModel
                ->where('product_id', $product['id'])
                ->where('is_delete', 0)  // Fetch only active variants
                ->findAll();
        }

        return $products;
    }
    public function getProductByCategoryId($categoryId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('product');
        $builder->select('product.id, product.product_name, product.row_order');
        $builder->join('product_categories pc', 'pc.product_id = product.id', 'inner');
        $builder->join('product_variants', 'product_variants.product_id = product.id', 'left');
        $builder->where('pc.category_id', $categoryId);
        $builder->where('product.is_delete', 0);
        $builder->where('product_variants.is_delete', 0);
        $builder->where('product.seller_id !=', 0);
        $builder->where('product.slug IS NOT NULL');
        $builder->where('product.slug !=', '');
        $builder->orderBy('product.row_order', 'ASC');
        $builder->groupBy('product.id');
        return $builder->get()->getResultArray();
    }
    public function isProductAvailable($productId, $variation, $price)
    {
        $result = $this->where([
            'id' => $productId,
            'variation LIKE' => '%' . $variation . '%',
            'price LIKE' => '%' . $price . '%',
            'stock' => 1,
            'status' => 1,
            'is_delete' => 0,
        ])->countAllResults();

        return $result === 1;
    }
    public function getPopularProducts($page, $rowPerPage = 6)
    {
        $start = ($page * $rowPerPage) - $rowPerPage;
        return $this->where('status', 1)
            ->where('popular', 1)
            ->where('deal_of_the_day', 0)
            ->where('is_delete', 0)
            ->findAll($rowPerPage, $start);
    }
    public function getProductsForFetchAllSubCategoryProductListByCategoryId($subcategoryId)
    {
        return $this->where('subcategory_id', $subcategoryId)
            ->where('status', 1)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->findAll();
    }
    public function getDealOfTheDayProducts($limit, $offset)
    {
        return $this->where([
            'status' => 1,
            'deal_of_the_day' => 1,
            'popular' => 0,
            'is_delete' => 0
        ])
            ->limit($limit, $offset)
            ->findAll();
    }

    // Method to fetch products by subcategory and category
    public function getProductsByCategory($subcategoryId, $categoryId, $limit = 6)
    {
        return $this->where('status', 1)
            ->where('subcategory_id', $subcategoryId)
            ->where('category_id', $categoryId)
            ->where('is_delete', 0)
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    // Method to get products with pagination and filtering conditions
    public function getOfferProducts($page, $rowPerPage)
    {
        $begin = ($page * $rowPerPage) - $rowPerPage;

        return $this->where('status', 1)
            ->where('is_delete', 0)
            ->groupStart()
            ->where('discount >', 0)
            ->orWhere('deal_of_the_day', 1)
            ->groupEnd()
            ->orderBy('id', 'desc')
            ->findAll($rowPerPage, $begin);
    }

    // Fetch product details by ID
    public function getProductById($id)
    {
        return $this->where('id', $id)->where('is_delete', 0)->first();
    }
    public function searchProductsByName($keyword)
    {
        return $this->where('status', 1)
            ->where('is_delete', 0)
            ->like('product_name', $keyword)
            ->findAll();
    }
    public function getTotalProducts()
    {
        return $this->join('product_variants', 'product_variants.product_id = product.id', 'left')->where('product.is_delete', 0)->where('product_variants.is_delete', 0)->countAllResults();
    }

    public function getTotalProductsForSeller()
    {
        return $this->select('COUNT(DISTINCT product.id) as total_products')
            ->join('product_variants', 'product_variants.product_id = product.id', 'left')
            ->where('product.is_delete', 0)
            ->where('product_variants.is_delete', 0)
            ->where('product.seller_id', session()->get('user_id'))
            ->get()
            ->getRowArray()['total_products'];
    }


    //used in home contrller
    public function getAllPopularProduct($limit, $sort_by)
    {
        $sellerModel = new SellerModel();
        $productVariantModel = new ProductVariantsModel();
        $cartsModel = new CartsModel();
        $orderProductsModel = new OrderProductModel();
        $ratingsModel = new ProductRatingsModel();
        $products = [];

        // Logged-in user (optional)
        $user = null;
        if (session()->has('email')) {
            $userModel = new UserModel();
            $user = $userModel->where('email', session()->get('email'))
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->first();
        }

        // Get all sellers by city
        $sellers = $sellerModel->select('id')
            ->where('city_id', session()->get('city_id'))
            ->findAll();

        $sellerIds = array_column($sellers, 'id');
        if (empty($sellerIds)) {
            return [];
        }

        //  Build optimized query with sorting at DB level
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' p');

        $builder->select('p.*, 
        MIN(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as min_price,
        MAX(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as max_price,
        COALESCE(sales.total_sold, 0) as total_sales,
        COALESCE(ratings.avg_rate, 0) as avg_rating')
            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'left')
            ->join(
                '(SELECT product_id, SUM(quantity) as total_sold FROM order_products GROUP BY product_id) sales',
                'sales.product_id = p.id',
                'left'
            )
            ->join(
                '(SELECT product_id, AVG(rate) as avg_rate FROM product_ratings WHERE is_active = 1 AND is_delete = 0 GROUP BY product_id) ratings',
                'ratings.product_id = p.id',
                'left'
            )
            ->where('p.popular', 1)
            ->where('p.is_delete', 0)
            ->whereIn('p.seller_id', $sellerIds)
            ->groupBy('p.id');

        //  Apply sorting at database level
        switch ($sort_by) {
            case 'alphabetical':
                $builder->orderBy('p.product_name', 'ASC');
                break;
            case 'low_to_high':
                $builder->orderBy('min_price', 'ASC');
                break;
            case 'high_to_low':
                $builder->orderBy('max_price', 'DESC');
                break;
            case 'maximum_discount':
                // Calculate discount percentage in the query
                $builder->select('p.*, 
                MIN(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as min_price,
                MAX(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as max_price,
                COALESCE(sales.total_sold, 0) as total_sales,
                COALESCE(ratings.avg_rate, 0) as avg_rating,
                MAX(CASE 
                    WHEN pv.price > 0 AND pv.discounted_price > 0 AND pv.discounted_price < pv.price 
                    THEN ((pv.price - pv.discounted_price) / pv.price) * 100 
                    ELSE 0 
                END) as max_discount', false);
                $builder->orderBy('max_discount', 'DESC');
                break;
            case 'best_selling':
                $builder->orderBy('total_sales', 'DESC');
                break;
            case 'best_rated':
                $builder->orderBy('avg_rating', 'DESC');
                break;
            default:
                $builder->orderBy('p.id', 'DESC');
                break;
        }

        //  Apply limit at database level
        if ($limit) {
            $builder->limit($limit);
        }

        $allProducts = $builder->get()->getResultArray();

        if (empty($allProducts)) {
            return [];
        }

        $productIds = array_column($allProducts, 'id');

        //  Fetch variants for limited products only
        $allVariants = $productVariantModel->whereIn('product_id', $productIds)
            ->where('is_delete', 0)
            ->findAll();

        $variantsByProduct = [];
        foreach ($allVariants as $v) {
            $variantsByProduct[$v['product_id']][] = $v;
        }

        //  Get cart data for limited products only
        $cartData = [];
        if (isset($user['id'])) {
            $cartItems = $cartsModel
                ->select('product_id, quantity')
                ->where('user_id', $user['id'])
                ->whereIn('product_id', $productIds)
                ->findAll();

            foreach ($cartItems as $item) {
                $cartData[$item['product_id']] = (int)$item['quantity'];
            }
        }

        // Build products array
        foreach ($allProducts as $product) {
            $variants = $variantsByProduct[$product['id']] ?? [];
            if (empty($variants)) {
                continue; // skip if no variants
            }

            // Sort variants by effective selling price (discounted or regular)
            usort($variants, function ($a, $b) {
                $aPrice = ($a['discounted_price'] > 0) ? $a['discounted_price'] : $a['price'];
                $bPrice = ($b['discounted_price'] > 0) ? $b['discounted_price'] : $b['price'];
                return $aPrice <=> $bPrice;
            });

            // Get cheapest variant (the one shown in list)
            $cheapestVariant = $variants[0];

            $price = (float) $cheapestVariant['price'];
            $discounted = (float) ($cheapestVariant['discounted_price'] > 0 ? $cheapestVariant['discounted_price'] : $cheapestVariant['price']);

            if ($price > 0 && $discounted < $price) {
                $maxDiscount = round((($price - $discounted) / $price) * 100, 2);
            } else {
                $maxDiscount = 0;
            }

            // Set price info for consistency
            $minPrice = (float) ($cheapestVariant['discounted_price'] > 0 ? $cheapestVariant['discounted_price'] : $cheapestVariant['price']);
            $maxPrice = $minPrice; // since display variant = cheapest variant

            // Attach computed fields
            $product['variants'] = $variants;
            $product['min_price'] = $minPrice;
            $product['max_price'] = $maxPrice;
            $product['max_discount'] = $maxDiscount;
            $product['total_sales'] = (int)$product['total_sales'];
            $product['avg_rating'] = (float)$product['avg_rating'];
            $product['cart_quantity'] = $cartData[$product['id']] ?? 0;

            $products[] = $product;
        }

        return $products;
    }

    public function getAllDealOfTheDayProduct($limit, $sort_by)
    {
        $sellerModel = new SellerModel();
        $productVariantModel = new ProductVariantsModel();
        $cartsModel = new CartsModel();
        $orderProductsModel = new OrderProductModel();
        $ratingsModel = new ProductRatingsModel();
        $products = [];

        // Logged-in user (optional)
        $user = null;
        if (session()->has('email')) {
            $userModel = new UserModel();
            $user = $userModel->where('email', session()->get('email'))
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->first();
        }

        // Get all sellers by city
        $sellers = $sellerModel->select('id')
            ->where('city_id', session()->get('city_id'))
            ->findAll();

        $sellerIds = array_column($sellers, 'id');
        if (empty($sellerIds)) {
            return [];
        }

        //  Build optimized query with sorting at DB level
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' p');

        $builder->select('p.*, 
        MIN(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as min_price,
        MAX(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as max_price,
        COALESCE(sales.total_sold, 0) as total_sales,
        COALESCE(ratings.avg_rate, 0) as avg_rating')
            ->join('product_variants pv', 'pv.product_id = p.id AND pv.is_delete = 0', 'left')
            ->join(
                '(SELECT product_id, SUM(quantity) as total_sold FROM order_products GROUP BY product_id) sales',
                'sales.product_id = p.id',
                'left'
            )
            ->join(
                '(SELECT product_id, AVG(rate) as avg_rate FROM product_ratings WHERE is_active = 1 AND is_delete = 0 GROUP BY product_id) ratings',
                'ratings.product_id = p.id',
                'left'
            )
            ->where('p.deal_of_the_day', 1)
            ->where('p.is_delete', 0)
            ->whereIn('p.seller_id', $sellerIds)
            ->groupBy('p.id');

        //  Apply sorting at database level
        switch ($sort_by) {
            case 'alphabetical':
                $builder->orderBy('p.product_name', 'ASC');
                break;
            case 'low_to_high':
                $builder->orderBy('min_price', 'ASC');
                break;
            case 'high_to_low':
                $builder->orderBy('max_price', 'DESC');
                break;
            case 'maximum_discount':
                // Calculate discount percentage in the query
                $builder->select('p.*, 
                MIN(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as min_price,
                MAX(CASE WHEN pv.discounted_price > 0 THEN pv.discounted_price ELSE pv.price END) as max_price,
                COALESCE(sales.total_sold, 0) as total_sales,
                COALESCE(ratings.avg_rate, 0) as avg_rating,
                MAX(CASE 
                    WHEN pv.price > 0 AND pv.discounted_price > 0 AND pv.discounted_price < pv.price 
                    THEN ((pv.price - pv.discounted_price) / pv.price) * 100 
                    ELSE 0 
                END) as max_discount', false);
                $builder->orderBy('max_discount', 'DESC');
                break;
            case 'best_selling':
                $builder->orderBy('total_sales', 'DESC');
                break;
            case 'best_rated':
                $builder->orderBy('avg_rating', 'DESC');
                break;
            default:
                $builder->orderBy('p.id', 'DESC');
                break;
        }

        //  Apply limit at database level
        if ($limit) {
            $builder->limit($limit);
        }

        $allProducts = $builder->get()->getResultArray();

        if (empty($allProducts)) {
            return [];
        }

        $productIds = array_column($allProducts, 'id');

        //  Fetch variants for limited products only
        $allVariants = $productVariantModel->whereIn('product_id', $productIds)
            ->where('is_delete', 0)
            ->findAll();

        $variantsByProduct = [];
        foreach ($allVariants as $v) {
            $variantsByProduct[$v['product_id']][] = $v;
        }

        //  Get cart data for limited products only
        $cartData = [];
        if (isset($user['id'])) {
            $cartItems = $cartsModel
                ->select('product_id, quantity')
                ->where('user_id', $user['id'])
                ->whereIn('product_id', $productIds)
                ->findAll();

            foreach ($cartItems as $item) {
                $cartData[$item['product_id']] = (int)$item['quantity'];
            }
        }

        // Build products array
        foreach ($allProducts as $product) {
            $variants = $variantsByProduct[$product['id']] ?? [];
            if (empty($variants)) {
                continue; // skip if no variants
            }

            // Sort variants by effective selling price (discounted or regular)
            usort($variants, function ($a, $b) {
                $aPrice = ($a['discounted_price'] > 0) ? $a['discounted_price'] : $a['price'];
                $bPrice = ($b['discounted_price'] > 0) ? $b['discounted_price'] : $b['price'];
                return $aPrice <=> $bPrice;
            });

            // Get cheapest variant (the one shown in list)
            $cheapestVariant = $variants[0];

            $price = (float) $cheapestVariant['price'];
            $discounted = (float) ($cheapestVariant['discounted_price'] > 0 ? $cheapestVariant['discounted_price'] : $cheapestVariant['price']);

            if ($price > 0 && $discounted < $price) {
                $maxDiscount = round((($price - $discounted) / $price) * 100, 2);
            } else {
                $maxDiscount = 0;
            }

            // Set price info for consistency
            $minPrice = (float) ($cheapestVariant['discounted_price'] > 0 ? $cheapestVariant['discounted_price'] : $cheapestVariant['price']);
            $maxPrice = $minPrice; // since display variant = cheapest variant

            // Attach computed fields
            $product['variants'] = $variants;
            $product['min_price'] = $minPrice;
            $product['max_price'] = $maxPrice;
            $product['max_discount'] = $maxDiscount;
            $product['total_sales'] = (int)$product['total_sales'];
            $product['avg_rating'] = (float)$product['avg_rating'];
            $product['cart_quantity'] = $cartData[$product['id']] ?? 0;

            $products[] = $product;
        }

        return $products;
    }


    public function similarProducts($subcategory_id)
    {
        $sellerModel = new SellerModel();
        $productVariantModel = new ProductVariantsModel();
        $cartsModel = new CartsModel();
        $products = [];

        // Check if session is set and get user details
        $user = null;
        if (session()->has('email')) {
            $userModel = new UserModel();
            $user = $userModel->where('email', session()->get('email'))
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->first();
        }

        // Get sellers based on city ID
        $sellers = $sellerModel->where('city_id', session()->get('city_id'))->findAll();

        // Get product IDs linked to this subcategory via the pivot table
        $productSubcategoryModel = new ProductSubcategoryModel();
        $linkedProductIds = array_column(
            $productSubcategoryModel->where('subcategory_id', $subcategory_id)->findAll(),
            'product_id'
        );

        foreach ($sellers as $seller) {
            if (empty($linkedProductIds)) {
                continue;
            }
            $sellerProducts = $this->whereIn('id', $linkedProductIds)
                ->where('is_delete', 0)
                ->where('seller_id', $seller['id'])
                ->findAll();

            if (empty($sellerProducts)) {
                // Fallback: show popular products from this seller
                $sellerProducts = $this->where('popular', 1)
                    ->where('is_delete', 0)
                    ->where('seller_id', $seller['id'])
                    ->findAll();
            }

            foreach ($sellerProducts as $product) {
                // 2) Fetch non-deleted variants for this product
                $variants = $productVariantModel
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
                $ratingRow = $this->db->query("SELECT AVG(rate) as avg_rating, COUNT(*) as rating_count FROM product_ratings WHERE product_id = ? AND is_active = 1 AND is_delete = 0", [$product['id']])->getRowArray();
                $product['avg_rating'] = $ratingRow ? round((float)$ratingRow['avg_rating'], 1) : 0;
                $product['rating_count'] = $ratingRow ? (int)$ratingRow['rating_count'] : 0;

                // 8) Finally, push into your output array
                $products[] = $product;
            }
        }

        $data['products'] = $products;
        return $data['products'];
    }

    public function categoryProducts($categories)
    {
        $sellerModel = new SellerModel();
        $productVariantModel = new ProductVariantsModel();
        $cartsModel = new CartsModel();
        $products = [];

        // Handle both single category_id and array of categories
        $categoryIds = [];
        if (is_array($categories)) {
            // If it's an array of category objects from product_categories table
            if (isset($categories[0]['category_id'])) {
                $categoryIds = array_column($categories, 'category_id');
            }
            // If it's already an array of IDs
            else {
                $categoryIds = $categories;
            }
        } else {
            // Single category ID
            $categoryIds = [$categories];
        }

        // Return empty if no categories
        if (empty($categoryIds)) {
            return [];
        }

        // Check if session is set and get user details
        $user = null;
        if (session()->has('email')) {
            $userModel = new UserModel();
            $user = $userModel->where('email', session()->get('email'))
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->first();
        }

        // Get sellers based on city ID
        $sellers = $sellerModel->where('city_id', session()->get('city_id'))->findAll();

        foreach ($sellers as $seller) {
            // Query products that belong to any of the specified categories
            $sellerProducts = $this->select('product.*')
                ->join('product_categories pc', 'pc.product_id = product.id', 'left')
                ->whereIn('pc.category_id', $categoryIds)
                ->where('product.is_delete', 0)
                ->where('product.seller_id', $seller['id'])
                ->groupBy('product.id')
                ->findAll();

            foreach ($sellerProducts as $product) {
                // Fetch non-deleted variants for this product
                $variants = $productVariantModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0)
                    ->findAll();

                // Only proceed if we actually got at least one variant
                if (empty($variants)) {
                    continue;
                }

                // Attach the variants array
                $product['variants'] = $variants;

                // Default cart_quantity to zero
                $product['cart_quantity'] = 0;

                // If the user is logged in, see if they have this in their cart
                if (isset($user['id'])) {
                    $cartItem = $cartsModel
                        ->where('user_id', $user['id'])
                        ->where('product_id', $product['id'])
                        ->first();
                    if ($cartItem) {
                        $product['cart_quantity'] = (int) $cartItem['quantity'];
                    }
                }

                // Attach rating data
                $ratingRow = $this->db->query("SELECT AVG(rate) as avg_rating, COUNT(*) as rating_count FROM product_ratings WHERE product_id = ? AND is_active = 1 AND is_delete = 0", [$product['id']])->getRowArray();
                $product['avg_rating'] = $ratingRow ? round((float)$ratingRow['avg_rating'], 1) : 0;
                $product['rating_count'] = $ratingRow ? (int)$ratingRow['rating_count'] : 0;

                // Finally, push into your output array
                $products[] = $product;
            }
        }

        return $products;
    }
    // Count low stock products
    public function countLowStockProducts($threshold = 10)
    {
        return $this->db->table('product_variants')
            ->join('product', 'product_variants.product_id = product.id')
            ->where('product_variants.is_unlimited_stock', 0)
            ->where('product_variants.stock >', 0)
            ->where('product_variants.stock <', $threshold)
            ->where('product_variants.is_delete', 0)
            ->countAllResults();
    }

    public function countLowStockProductsForSeller($threshold = 10)
    {
        return $this->db->table('product_variants')
            ->join('product', 'product_variants.product_id = product.id')
            ->where('product_variants.is_unlimited_stock', 0)
            ->where('product.seller_id', session()->get('user_id'))
            ->where('product_variants.stock >', 0)
            ->where('product_variants.stock <', $threshold)
            ->where('product_variants.is_delete', 0)
            ->countAllResults();
    }

    // Count out of stock products
    public function countOutOfStockProducts()
    {
        return $this->db->table('product_variants')
            ->join('product', 'product_variants.product_id = product.id')
            ->where('product_variants.is_unlimited_stock', 0)
            ->where('product_variants.stock', 0)
            ->where('product_variants.is_delete', 0)
            ->countAllResults();
    }

    public function countOutOfStockProductsForSeller()
    {
        return $this->db->table('product_variants')
            ->join('product', 'product_variants.product_id = product.id')
            ->where('product_variants.is_unlimited_stock', 0)
            ->where('product.seller_id', session()->get('user_id'))
            ->where('product_variants.stock', 0)
            ->where('product_variants.is_delete', 0)
            ->countAllResults();
    }
}
