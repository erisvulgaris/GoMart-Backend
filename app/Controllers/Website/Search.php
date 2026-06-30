<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;
use App\Models\CartsModel;
use App\Models\ProductModel;
use App\Models\ProductRatingsModel;
use App\Models\ProductTagModel;
use App\Models\ProductVariantsModel;
use App\Models\SellerModel;
use App\Models\TagsModel;
use App\Models\UserModel;

class Search extends BaseController
{
    public function index()
    {
        $data['settings'] = $this->settings;
        $data['country'] = $this->country;
        date_default_timezone_set($this->timeZone['timezone']);

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

        // Handle GET request with query parameter
        if ($this->request->is('get')) {
            $searchQuery = $this->request->getGet('q');
            $data['searchQuery'] = $searchQuery ?? '';

            // If there's a search query, perform the search
            if ($searchQuery && strlen($searchQuery) > 0) {
                $searchResults = $this->performSearch($searchQuery, $user);
                $data['products'] = $searchResults['products'];
                $data['hasSearched'] = true;
            } else {
                $data['products'] = [];
                $data['hasSearched'] = false;
            }

            return view('website/search/search', $data);
        }

        // Handle POST request (AJAX)
        if ($this->request->is('post')) {
            $dataInput = $this->request->getJSON(true);
            $searchTerm = $dataInput['searchStr'] ?? '';

            if (strlen($searchTerm) > 0) {
                $searchResults = $this->performSearch($searchTerm, $user);
                return $this->response->setJSON([
                    'status' => 'success',
                    'products' => $searchResults['products'],
                    'currency_symbol_position' => $this->settings['currency_symbol_position'],
                    'currency_symbol' => $this->country['currency_symbol']
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'products' => [],
                'currency_symbol_position' => $this->settings['currency_symbol_position'],
                'currency_symbol' => $this->country['currency_symbol']
            ]);
        }
    }


    public function popularSearch()
    {
        $userModel = new UserModel();
        $user = null;

        if (session()->get('login_type') == 'email') {
            $user = $userModel->where('email', session()->get('email'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        if (session()->get('login_type') == 'mobile') {
            $user = $userModel->where('mobile', session()->get('mobile'))->where('is_active', 1)->where('is_delete', 0)->first();
        }

        $trendingSearches = [];
        if (!empty($this->settings['popular_search'])) {
            if (is_string($this->settings['popular_search'])) {
                $decoded = json_decode($this->settings['popular_search'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $trendingSearches = $decoded;
                } else {
                    $trendingSearches = array_map('trim', explode(',', $this->settings['popular_search']));
                }
            } elseif (is_array($this->settings['popular_search'])) {
                $trendingSearches = $this->settings['popular_search'];
            }

            $trendingSearches = array_filter($trendingSearches, function ($value) {
                return !empty(trim($value));
            });
        }

        $productModel = new ProductModel();
        $productVariantModel = new ProductVariantsModel();
        $cartsModel = new CartsModel();
        $sellerModel = new SellerModel();

        $sellers = $sellerModel->where('city_id', session()->get('city_id'))->findAll();
        $sellerIds = array_column($sellers, 'id');

        $trendingProducts = [];
        $productRatingsModel = new ProductRatingsModel();
        if (!empty($sellerIds)) {
            $products = $productModel
                ->whereIn('seller_id', $sellerIds)
                ->where('is_delete', 0)
                ->where('popular', 1)
                ->orderBy('id', 'DESC')
                ->limit(8)
                ->findAll();

            foreach ($products as &$product) {
                $variants = $productVariantModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0)
                    ->limit(1)
                    ->findAll();

                if (!empty($variants)) {
                    $variant = $variants[0];

                    if ($variant['price'] > 0 && $variant['discounted_price'] > 0) {
                        $variant['discountPercentage'] = round((($variant['price'] - $variant['discounted_price']) / $variant['price']) * 100);
                    } else {
                        $variant['discountPercentage'] = 0;
                    }

                    $product['variants'] = [$variant];
                    $product['cart_quantity'] = 0;
                    if ($user) {
                        $cartItem = $cartsModel
                            ->where('user_id', $user['id'])
                            ->where('product_id', $product['id'])
                            ->first();

                        if ($cartItem) {
                            $product['cart_quantity'] = $cartItem['quantity'];
                        }
                    }

                    // Add rating data
                    $ratings = $productRatingsModel->where('product_id', $product['id'])->where('is_active', 1)->where('is_delete', 0)->findAll();
                    $ratingCount = count($ratings);
                    $product['avg_rating'] = $ratingCount > 0 ? round(array_sum(array_column($ratings, 'rate')) / $ratingCount, 1) : 0;
                    $product['rating_count'] = $ratingCount;

                    $trendingProducts[] = $product;
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'searches' => array_values($trendingSearches),
            'products' => $trendingProducts,
            'currency_symbol_position' => $this->settings['currency_symbol_position'],
            'currency_symbol' => $this->country['currency_symbol']
        ]);
    }

    private function performSearch($searchTerm, $user = null)
    {
        $sellerModel = new SellerModel();
        $productVariantModel = new ProductVariantsModel();
        $cartsModel = new CartsModel();
        $productModel = new ProductModel();
        $tagsModel = new TagsModel();
        $productTagModel = new ProductTagModel();
        $productRatingsModel = new ProductRatingsModel();
        $products = [];

        $tags = $tagsModel->like('name', $searchTerm)->findAll();
        $tagIds = array_column($tags, 'id');
        $productTags = !empty($tagIds) ? $productTagModel->whereIn('tag_id', $tagIds)->findAll() : [];
        $tagProductIds = array_column($productTags, 'product_id');

        $sellers = $sellerModel->where('city_id', session()->get('city_id'))->findAll();

        foreach ($sellers as $seller) {
            $productQuery = $productModel
                ->where('is_delete', 0)
                ->where('seller_id', $seller['id'])
                ->groupStart()
                ->like('product_name', $searchTerm);

            if (!empty($tagProductIds)) {
                $productQuery->orWhereIn('id', $tagProductIds);
            }

            $sellerProducts = $productQuery->groupEnd()->findAll();

            foreach ($sellerProducts as &$product) {
                $product['variants'] = $productVariantModel
                    ->where('product_id', $product['id'])
                    ->where('is_delete', 0)
                    ->findAll();

                if (!empty($product['variants'])) {
                    foreach ($product['variants'] as &$variant) {
                        if ($variant['price'] > 0 && $variant['discounted_price'] > 0) {
                            $variant['discountPercentage'] = round((($variant['price'] - $variant['discounted_price']) / $variant['price']) * 100);
                        } else {
                            $variant['discountPercentage'] = 0;
                        }
                    }

                    $product['cart_quantity'] = 0;
                    if ($user) {
                        $cartItem = $cartsModel
                            ->where('user_id', $user['id'])
                            ->where('product_id', $product['id'])
                            ->first();

                        if ($cartItem) {
                            $product['cart_quantity'] = $cartItem['quantity'];
                        }
                    }

                    $ratings = $productRatingsModel->where('product_id', $product['id'])->where('is_active', 1)->where('is_delete', 0)->findAll();
                    $ratingCount = count($ratings);
                    $product['avg_rating'] = $ratingCount > 0 ? round(array_sum(array_column($ratings, 'rate')) / $ratingCount, 1) : 0;
                    $product['rating_count'] = $ratingCount;

                    $products[] = $product;
                }
            }
        }

        return ['products' => $products];
    }
}
