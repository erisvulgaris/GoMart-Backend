<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;
use App\Models\SellerModel;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductSortTypeModel;
use App\Models\ProductModel; // Assuming exists based on project
use App\Models\ProductVariantsModel;

use App\Models\CartsModel;
use App\Models\UserModel;

class Sellers extends BaseController
{
    protected $sellersModel;
    protected $productModel;

    public function __construct()
    {
        $this->sellersModel = new SellerModel();
        $this->productModel = new ProductModel();
    }

    // Single seller details + products
    public function getSellerPage($slug = null)
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

        $seller = $this->sellersModel->where('slug', $slug)->where('status', 1)->where('is_delete', 0)->first();
        if (!$seller) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Seller not found');
        }

        $data['seller_details'] = $seller;
        $data['seller'] = $seller['store_name'];

        $categoryModel = new CategoryModel();
        $data['categorys'] = $this->settings['frontend_category_section'] == 1 ? $categoryModel->orderBy('row_order', 'asc')->findAll() : [];

        $brandModel = new BrandModel();
        $data['brands'] = $this->settings['frontend_brand_section'] == 1 ? $brandModel->orderBy('row_order', 'asc')->findAll() : [];

        $data['sellers'] = [];

        $productSortTypeModel = new ProductSortTypeModel();
        $data['productSorts'] = $productSortTypeModel->findAll();

        $data['is_mobile'] = preg_match('/(iphone|ipod|android|blackberry|mobile|tablet|kindle|mobi|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

        $data['is_seller'] = true;
        $data['seller_slug'] = $slug;

        return view('website/sellers/sellers', $data);
    }
}
