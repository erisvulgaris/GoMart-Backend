<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubcategoryModel;
use App\Models\BrandModel;
use App\Models\CartsModel;
use App\Models\CategoryModel;
use App\Models\CountryModel;
use App\Models\ProductImagesModel;
use App\Models\ProductModel;
use App\Models\ProductRatingsModel;
use App\Models\ProductTagModel;
use App\Models\ProductVariantsModel;
use App\Models\SellerCategoriesModel;
use App\Models\SellerModel;
use App\Models\SettingsModel;
use App\Models\TagsModel;
use App\Models\TaxModel;
use App\Models\ProductTaxModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductSubcategoryModel;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class Product extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_add('product')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getCategories();

            $sellerModel = new SellerModel();
            $sellers = $sellerModel->where('status', 1)->where('is_delete', 0)->findAll();

            $brandModel = new BrandModel();
            $brands = $brandModel->findAll();

            $taxModel = new TaxModel();
            $taxes = $taxModel->where('is_active', 1)->where('is_delete', 0)->findAll();
            $settingModel = new SettingsModel();

            return view('product/add', [
                'settings' => $settingModel->getSettings(),
                'categories' => $categories,
                'sellers' => $sellers,
                'taxes' => $taxes,
                'brands' => $brands

            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }
    public function view()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('product')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getCategories();
            $sellerModel = new SellerModel();
            $sellers = $sellerModel->where('status', 1)->where('is_delete', 0)->findAll();

            return view('product/list', [
                'settings' => $settingModel->getSettings(),
                'categories' => $categories,
                'sellers' => $sellers,
            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }
    public function list()
    {
        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $category = $this->request->getPost('category');
        $seller = $this->request->getPost('seller');
        $status = $this->request->getPost('status');
        $stock = $this->request->getPost('stock');



        $productModel = new ProductModel();
        $products = $productModel->getProductsList($category, $seller,  $status, $stock);
        $output['data'] = [];
        $x = 1;
        $countryModel = new CountryModel();
        $country = $countryModel->where('is_active', 1)->first();
        foreach ($products as $row) {
            foreach ($row['variants'] as $variants) {
                $img = "<a href='" . base_url($row['main_img']) . "' target='_blank'>
                    <img class='media-object round-media' src='" . base_url($row['main_img']) . "' alt='image' style='height: 60px; width: 40%'>
                </a>";
                $action = "<a data-tooltip='tooltip' title='Edit Product' href='" . base_url("admin/product/edit/{$row['id']}") . "' class='btn btn-primary-light  btn-xs'>
                    <i class='fi fi-tr-customize-edit'></i></a>
                   <a type='button' data-tooltip='tooltip' title='Delete product' onclick='deleteproduct({$row['id']}, {$variants['id']})' class='btn btn-danger-light btn-xs'>
                    <i class='fi fi-tr-trash-xmark'></i></a> 
                    <a href='/admin/product/rating/{$row['id']}' data-tooltip='tooltip' title='Product rating' class='m-1 btn btn-warning-light btn-xs'>
                    <i class='fi fi-tr-feedback-review'></i></a>";

                // Stock status

                // Publish status
                $publish = $row['status'] == 1 ? "<span class='badge badge-success'>Published</span> " : "<span class='badge badge-danger'>Unpublish</span> ";
                if ($variants['is_unlimited_stock']) {
                    $stock =  "<span class='badge badge-success'>Unlimited</span>";
                } else {
                    $stock = $variants['stock'] > 0 ? "<span class='badge badge-success'>" . $variants['stock'] . " Available</span> " : "<span class='badge badge-danger'> Sold out</span> ";
                }
                // Product badges
                $productname = $row['product_name'];
                if ($row['popular'] == 1) {
                    $productname .= " <br> <span class='badge badge-success'>popular</span>";
                }
                if ($row['deal_of_the_day'] == 1) {
                    $productname .= " <span class='badge badge-success'>Deal Of The Day</span>";
                }

                if ($variants['discounted_price'] == 0) {
                    $price = $variants['price'];
                    $discounted_price = "";
                } else {
                    $price = "<p style='text-decoration:line-through' class='text-sm'> " . $country['currency_symbol'] . $variants['price'] . " </p>";
                    $discounted_price = $country['currency_symbol'] . " " . $variants['discounted_price'];
                }

                $productname .= " <button type='button' onclick='downloadQR({$row['id']})' class='btn btn-primary-light btn-xs'><i class='fi fi-tr-down-to-line'></i> QR</button>";

                // Prepare the output data
                $output['data'][] = [
                    $row['id'],
                    $variants['id'],
                    $productname,
                    $row['store_name'],  // Category name
                    $img,
                    $row['brand'],
                    $row['categories'],  // Category name
                    $row['subcategories'], // Subcategory name
                    $price, // Subcategory name
                    $discounted_price,
                    $variants['title'],
                    $stock,
                    $publish,
                    $action
                ];
                $x++;
            }
        }

        return $this->response->setJSON($output);
    }
    public function delete()
    {
        $output = ['success' => false];

        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_delete('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }
        // Check if product_id is set in the POST request
        if ($this->request->getPost('product_id')) {
            // Load the database connection
            $productModel = new ProductModel();
            $productVariantsModel = new ProductVariantsModel();
            $productId = $this->request->getPost('product_id');
            $varientId = $this->request->getPost('varientId');

            $findAvailableVarient = $productVariantsModel->select('COUNT(id) AS varientCount')->where('is_delete', 0)->where('product_id', $productId)->first();

            if ($findAvailableVarient['varientCount'] > 1) {
                $update = $productVariantsModel->update($varientId, ['is_delete' => 1]);
            } else {
                $update = $productVariantsModel->update($varientId, ['is_delete' => 1]);
                $update = $productModel->update($productId, ['is_delete' => 1]);
            }

            // Check if the update was successful
            if ($update) {

                $cartsModel = new CartsModel();
                $cartsModel->where('product_id', $productId)->delete();

                $output['success'] = true;
                $output['message'] = 'Producr deleted successfully';
            } else {
                $output['message'] = 'Something went wrong';
            }
        }

        // Return the output as JSON
        return $this->response->setJSON($output);
    }

    public function add()
    {
        $output = ['success' => false];
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_add('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }
        // Sanitize and validate inputs
        // $productname =  preg_replace('/[^A-Za-z0-9&\s]/', '', $this->request->getPost('productname'));
        $productname =  filter_var($this->request->getPost('productname'), FILTER_SANITIZE_STRING);

        $brandname = filter_var($this->request->getPost('brandname'), FILTER_SANITIZE_STRING);
        $categoryname = $this->request->getPost('categoryname');
        $subcategoryname = $this->request->getPost('subcategoryname');
        $ispublish = filter_var($this->request->getPost('ispublish'), FILTER_VALIDATE_INT);
        $popular = filter_var($this->request->getPost('popular'), FILTER_VALIDATE_INT);
        $deal_of_the_day = filter_var($this->request->getPost('deal_of_the_day'), FILTER_VALIDATE_INT);
        $description = $this->request->getPost('description');
        $seller = $this->request->getPost('seller');
        $tags = $this->request->getPost('tags');
        $ptype = $this->request->getPost('ptype');
        $manufacturer = $this->request->getPost('manufacturer');
        $made_in = $this->request->getPost('made_in');
        $tax_ids = $this->request->getPost('tax_ids') ?? [];
        $tax_included_in_price = $this->request->getPost('tax_included_in_price') ?? 0;
        $is_returnable = $this->request->getPost('is_returnable');
        $return_days = $this->request->getPost('return_days');
        $fssai_lic_no = $this->request->getPost('fssai_lic_no');
        $total_allowed_quantity = $this->request->getPost('total_allowed_quantity');
        $seo_title = $this->request->getPost('seo_title');
        $seo_keywords = $this->request->getPost('seo_keywords');
        $seo_alt_text = $this->request->getPost('seo_alt_text');
        $seo_description = $this->request->getPost('seo_description');

        // Handle main product image upload
        $db_file_path = '';
        if ($files = $this->request->getFiles()['main_files']) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/product/', $newName);
                    $db_file_path = 'uploads/product/' . $newName;
                }
            }
        }

        // Generate the initial slug
        $slug_prev = str_replace(" ", "-", $productname);
        $slug = preg_replace('/[^A-Za-z0-9-]/', '', strtolower($slug_prev));
        $slug1 = $slug;
        $productModel = new ProductModel();
        $check = true;
        $x = 1;
        while ($check) {
            $duplicateSlug = $productModel->where('slug', $slug1)->countAllResults();
            if ($duplicateSlug > 0) {
                $slug1 = $slug . $x;
            } else {
                $check = false;
            }
            $x++;
        }

        // Insert product details into the database
        $timestamp = date("Y-m-d H:i:s");
        $productData = [
            'product_name' => $productname,
            'main_img' => $db_file_path,
            'brand_id' => $brandname,
            'description' => $description,
            'slug' => $slug1,
            'date' => $timestamp,
            'status' => $ispublish,
            'popular' => $popular,
            'deal_of_the_day' => $deal_of_the_day,
            'seller_id' => $seller,
            'manufacturer' => $manufacturer,
            'made_in' => $made_in,
            'tax_included_in_price' => $tax_included_in_price,
            'return_days' => $return_days,
            'is_returnable' => $is_returnable,
            'fssai_lic_no' => $fssai_lic_no,
            'total_allowed_quantity' => $total_allowed_quantity,
            'seo_title' => $seo_title,
            'seo_keywords' => $seo_keywords,
            'seo_alt_text' => $seo_alt_text,
            'seo_description' => $seo_description
        ];

        $db = \Config\Database::connect();
        $db->transStart(); // Begin transaction

        $productid = $productModel->insertProduct($productData);

        $variantInserted = false;
        if ($productid) {
            // Handle multiple taxes
            if (!empty($tax_ids)) {
                $productTaxModel = new ProductTaxModel();
                $productTaxModel->syncProductTaxes($productid, $tax_ids);
            }
            // Handle additional product images (without variant_id)
            if ($this->request->getFiles()) {
                $productImagesModel = new ProductImagesModel();
                if (isset($this->request->getFiles()['additional_files'])) {
                    if ($files = $this->request->getFiles()['additional_files']) {
                        foreach ($files as $file) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = $file->getRandomName();
                                $file->move('uploads/product/', $newName);
                                $db_file_path = 'uploads/product/' . $newName;
                                $productImages = [
                                    'product_id' => $productid,
                                    'product_variant_id' => 0,
                                    'image' => $db_file_path,
                                ];
                                $productImagesModel->insert($productImages);
                            }
                        }
                    }
                }
            }

            // Handle tags
            $productTagModel = new ProductTagModel();
            $tagModel = new TagsModel();
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if ($tagModel->tagExists($tag) == false) {
                        $tag_id = $tagModel->addTag($tag);
                    } else {
                        $tagIdFromExist = $tagModel->getTagByName($tag);
                        $tag_id = $tagIdFromExist['id'];
                    }

                    $productTag = [
                        'product_id' => $productid,
                        'tag_id' => $tag_id,
                    ];
                    $productTagModel->insert($productTag);
                }
            }

            // Handle categories
            $categoryData = [];
            foreach ($categoryname as $categoryId) {
                $categoryData[] = [
                    'product_id' => $productid,
                    'category_id' => $categoryId
                ];
            }
            $productCategoryModel = new ProductCategoryModel();
            $productCategoryModel->insertBatch($categoryData);

            // Handle subcategories
            $subcategoryData = [];
            foreach ($subcategoryname as $subcategoryId) {
                $subcategoryData[] = [
                    'product_id' => $productid,
                    'subcategory_id' => $subcategoryId
                ];
            }
            $productSubcategoryModel = new ProductSubcategoryModel();
            $productSubcategoryModel->insertBatch($subcategoryData);

            // Store variant IDs mapped to their indexes
            $variantIdMap = [];

            // Handle product variants
            $productVariantsModel = new ProductVariantsModel();
            if ($ptype == "simple_product") {
                $simple_product_title = $this->request->getPost('simple_product_title');
                $simple_product_price = $this->request->getPost('simple_product_price');

                if (!empty($simple_product_title) && !empty($simple_product_price)) {
                    $simple_product_special_price = $this->request->getPost('simple_product_special_price');
                    $simple_product_stock = $this->request->getPost('simple_product_stock');
                    $simple_product_status = $this->request->getPost('simple_product_status');

                    $is_unlimited_stock = ($simple_product_stock == "") ? 1 : 0;

                    $variantData = [
                        'product_id' => $productid,
                        'title' => $simple_product_title,
                        'price' => $simple_product_price,
                        'discounted_price' => $simple_product_special_price,
                        'stock' => $simple_product_stock,
                        'status' => $simple_product_status,
                        'is_unlimited_stock' => $is_unlimited_stock
                    ];

                    $variantId = $productVariantsModel->insert($variantData);
                    if ($variantId) {
                        $variantInserted = true;
                        $variantIdMap['simple'] = $variantId;
                    }
                }
            } else {
                $inputData = $this->request->getPost();
                $variations = [];

                foreach ($inputData as $key => $value) {
                    if (preg_match('/variation_product_(\w+)_(\d+)/', $key, $matches)) {
                        $field = $matches[1];
                        $index = $matches[2];

                        if (!isset($variations[$index])) {
                            $variations[$index] = [];
                        }
                        $variations[$index][$field] = $value;
                    }
                }

                foreach ($variations as $index => $variation) {
                    if (!empty($variation['title']) && !empty($variation['price'])) {
                        $is_unlimited_stock = ($variation['stock'] == "") ? 1 : 0;

                        $variationData = [
                            'title' => $variation['title'],
                            'price' => $variation['price'],
                            'discounted_price' => $variation['special_price'] ?? 0,
                            'stock' => $variation['stock'] ?? 0,
                            'status' => $variation['status'] ?? 0,
                            'is_unlimited_stock' => $is_unlimited_stock,
                            'product_id' => $productid,
                        ];

                        $variantId = $productVariantsModel->insert($variationData);
                        if ($variantId) {
                            $variantInserted = true;
                            $variantIdMap[$index] = $variantId;
                        }
                    }
                }
            }

            // Handle variant images with variant_id
            if ($variantInserted) {
                $productImagesModel = new ProductImagesModel();
                $variantImagesFiles = $this->request->getFiles();
                $variantImageIndexes = $this->request->getPost('variant_image_indexes');

                if (isset($variantImagesFiles['variant_images']) && !empty($variantImageIndexes)) {
                    $variantImages = $variantImagesFiles['variant_images'];

                    foreach ($variantImages as $key => $file) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            if (isset($variantImageIndexes[$key])) {
                                $variantIndex = $variantImageIndexes[$key];

                                if (isset($variantIdMap[$variantIndex])) {
                                    $variantId = $variantIdMap[$variantIndex];

                                    $newName = $file->getRandomName();
                                    $file->move('uploads/product/', $newName);
                                    $db_file_path = 'uploads/product/' . $newName;

                                    $variantImageData = [
                                        'product_id' => $productid,
                                        'product_variant_id' => $variantId,
                                        'image' => $db_file_path,
                                    ];

                                    $productImagesModel->insert($variantImageData);
                                } else {
                                    log_message('error', "Variant index {$variantIndex} not found in variantIdMap");
                                }
                            } else {
                                log_message('error', "No variant index found for file at position {$key}");
                            }
                        }
                    }
                }

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    $output['message'] = 'Transaction failed.';
                } else {
                    $output['success'] = true;
                    $output['message'] = 'Product added successfully';
                }
            } else {
                $db->transRollback(); // No variant added, rollback
                $output['message'] = 'At least one valid variant is required.';
            }

        } else {
            $output['message'] = 'Something went wrong';
        }

        return $this->response->setJSON($output);
    }



    public function edit($id)
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_edit('product')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();
            $subcategoryModel = new SubcategoryModel();
            $productModel = new ProductModel();
            $product = $productModel->find($id);

            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getCategories();

            $brandModel = new BrandModel();
            $brands = $brandModel->findAll();

            $sellerModel = new SellerModel();
            $productCategoryModel = new ProductCategoryModel();

            $productCategoryIds = $productCategoryModel->where('product_id', $id)->findColumn('category_id') ?? [];

            $sellerQuery = $sellerModel->select('seller.id, seller.store_name')
                ->join('seller_categories', 'seller_categories.seller_id = seller.id', 'left')
                ->join('product_categories', 'product_categories.category_id = seller_categories.category_id AND seller_categories.seller_id = seller.id', 'left')
                ->groupStart();

            if (!empty($productCategoryIds)) {
                $sellerQuery = $sellerQuery->whereIn('seller_categories.category_id', $productCategoryIds)
                    ->orWhere('seller.id', $product['seller_id']);
            } else {
                $sellerQuery = $sellerQuery->where('seller.id', $product['seller_id']);
            }

            $sellers = $sellerQuery->groupEnd()->distinct()->findAll();


            $taxModel = new TaxModel();
            $taxes = $taxModel->where('is_active', 1)->where('is_delete', 0)->findAll();

            $productTaxModel = new ProductTaxModel();
            $selectedTaxIds = $productTaxModel->where('product_id', $id)->findColumn('tax_id') ?? [];

            $productTagModel = new ProductTagModel();
            $tags = $productTagModel
                ->select('tags.id, tags.name')
                ->join('tags', 'product_tag.tag_id = tags.id')
                ->where('product_tag.product_id', $id)
                ->findAll();

            $productVariantsModel = new ProductVariantsModel();
            $variation = $productVariantsModel
                ->where('product_id', $id)
                ->where('is_delete', 0)
                ->findAll();

            $productImagesModel = new ProductImagesModel();

            // Get general product images (without variant_id)
            $images = $productImagesModel
                ->where('product_id', $id)
                ->where('product_variant_id', 0)
                ->findAll();

            // Get ALL variant images grouped by variant_id
            $variantImagesRaw = $productImagesModel
                ->where('product_id', $id)
                ->where('product_variant_id !=', 0)
                ->where('product_variant_id IS NOT NULL')
                ->findAll();

            // Group variant images by variant_id for easy access
            $variantImages = [];
            foreach ($variantImagesRaw as $vImg) {
                $variantImages[$vImg['product_variant_id']][] = $vImg;
            }

            
            $selectedCategories = $productCategoryModel
                ->where('product_id', $id)
                ->findAll();

            $productSubcategoryModel = new ProductSubcategoryModel();
            $selectedSubcategories = $productSubcategoryModel
                ->where('product_id', $id)
                ->findAll();

            $categoryIds = array_column($categories, 'id');
            $subcategories = $subcategoryModel
                ->whereIn('category_id', $categoryIds)
                ->findAll();

            return view('product/edit', [
                'categories' => $categories,
                'settings' => $settingModel->getSettings(),
                'subcategories' => $subcategories,
                'product' => $product,
                'brands' => $brands,
                'sellers' => $sellers,
                'tags' => $tags,
                'taxes' => $taxes,
                'selectedTaxIds' => $selectedTaxIds,
                'variations' => $variation,
                'images' => $images,
                'variantImages' => $variantImages,
                'selectedCategories' => $selectedCategories,
                'selectedSubcategories' => $selectedSubcategories
            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    public function update()
    {
        $output = ['success' => false];
        
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        
        if (!can_edit('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
    
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }
    
        // Sanitize and validate inputs
        $productname = preg_replace('/[^A-Za-z0-9&\s]/', '', $this->request->getPost('productname'));
        $brandname = $this->request->getPost('brandname');
        $categoryname = $this->request->getPost('categoryname');
        $subcategoryname = $this->request->getPost('subcategoryname');
        $ispublish = filter_var($this->request->getPost('ispublish'), FILTER_VALIDATE_INT);
        $popular = filter_var($this->request->getPost('popular'), FILTER_VALIDATE_INT);
        $deal_of_the_day = filter_var($this->request->getPost('deal_of_the_day'), FILTER_VALIDATE_INT);
        $description = $this->request->getPost('description');
        $seller = $this->request->getPost('seller');
        $tags = $this->request->getPost('tags');
        $manufacturer = $this->request->getPost('manufacturer');
        $made_in = $this->request->getPost('made_in');
        $tax_ids = $this->request->getPost('tax_ids') ?? [];
        $tax_included_in_price = $this->request->getPost('tax_included_in_price') ?? 0;
        $is_returnable = $this->request->getPost('is_returnable');
        $return_days = $this->request->getPost('return_days');
        $fssai_lic_no = $this->request->getPost('fssai_lic_no');
        $product_id = (int) $this->request->getPost('edit_id');
        $seo_title = $this->request->getPost('seo_title');
        $seo_keywords = $this->request->getPost('seo_keywords');
        $seo_alt_text = $this->request->getPost('seo_alt_text');
        $seo_description = $this->request->getPost('seo_description');
    
        $db_file_path = '';
        $productModel = new ProductModel();
    
        // Handle file uploads
        if ($this->request->getFiles()) {
            $productImagesModel = new ProductImagesModel();
            
            if (isset($this->request->getFiles()['main_files'])) {
                if ($files = $this->request->getFiles()['main_files']) {
                    foreach ($files as $file) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = $file->getRandomName();
                            $file->move('uploads/product/', $newName);
                            $db_file_path = 'uploads/product/' . $newName;
                            $dataMainImg = ['main_img' => $db_file_path];
                            $productModel->where('id', $product_id)->set($dataMainImg)->update();
                        }
                    }
                }
            }
    
            if (isset($this->request->getFiles()['additional_files'])) {
                if ($files = $this->request->getFiles()['additional_files']) {
                    foreach ($files as $file) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = $file->getRandomName();
                            $file->move('uploads/product/', $newName);
                            $db_file_path = 'uploads/product/' . $newName;
                            $productImages = [
                                'product_id' => $product_id,
                                'product_variant_id' => 0,
                                'image' => $db_file_path,
                            ];
                            $productImagesModel->insert($productImages);
                        }
                    }
                }
            }
        }
    
        // Update product details
        $timestamp = date("Y-m-d H:i:s");
        $productData = [
            'product_name' => $productname,
            'brand_id' => $brandname,
            'description' => $description,
            'date' => $timestamp,
            'status' => $ispublish,
            'popular' => $popular,
            'deal_of_the_day' => $deal_of_the_day,
            'seller_id' => $seller,
            'manufacturer' => $manufacturer,
            'made_in' => $made_in,
            'tax_included_in_price' => $tax_included_in_price,
            'return_days' => $return_days,
            'is_returnable' => $is_returnable,
            'fssai_lic_no' => $fssai_lic_no,
            'seo_title' => $seo_title,
            'seo_keywords' => $seo_keywords,
            'seo_alt_text' => $seo_alt_text,
            'seo_description' => $seo_description
        ];

        $productModel->where('id', $product_id)->set($productData)->update();

        // Handle multiple taxes
        $productTaxModel = new ProductTaxModel();
        $productTaxModel->syncProductTaxes($product_id, $tax_ids);

        // Handle tags
        $productTagModel = new ProductTagModel();
        $tagModel = new TagsModel();
        if (is_array($tags)) {
            $productTagModel->where('product_id', $product_id)->delete();
            foreach ($tags as $tag) {
                if ($tagModel->tagExists($tag) == false) {
                    $tag_id = $tagModel->addTag($tag);
                } else {
                    $tagIdFromExist = $tagModel->getTagByName($tag);
                    $tag_id = $tagIdFromExist['id'];
                }
                $productTag = [
                    'product_id' => $product_id,
                    'tag_id' => $tag_id,
                ];
                $productTagModel->insert($productTag);
            }
        }

        // Handle categories
        $categoryData = [];
        foreach ($categoryname as $categoryId) {
            $categoryData[] = [
                'product_id' => $product_id,
                'category_id' => $categoryId
            ];
        }
        $productCategoryModel = new ProductCategoryModel();
        $productCategoryModel->where('product_id', $product_id)->delete();
        $productCategoryModel->insertBatch($categoryData);

        // Handle subcategories
        $subcategoryData = [];
        foreach ($subcategoryname as $subcategoryId) {
            $subcategoryData[] = [
                'product_id' => $product_id,
                'subcategory_id' => $subcategoryId
            ];
        }
        $productSubcategoryModel = new ProductSubcategoryModel();
        $productSubcategoryModel->where('product_id', $product_id)->delete();
        $productSubcategoryModel->insertBatch($subcategoryData);

        // Handle product variants
        $productVariantsModel = new ProductVariantsModel();
        $inputData = $this->request->getPost();

        // Store variant IDs mapped to their indexes
        $variantIdMap = [];

        // Parse dynamic data into a structured array
        $variations = [];
        foreach ($inputData as $key => $value) {
            if (preg_match('/variation_product_(\w+)_(\d+)/', $key, $matches)) {
                $field = $matches[1];
                $index = $matches[2];

                if (!isset($variations[$index])) {
                    $variations[$index] = [];
                }
                $variations[$index][$field] = $value;
            }
        }

        foreach ($variations as $index => $variation) {
            $is_unlimited_stock = ($variation['stock'] == "") ? 1 : 0;

            if (isset($variation['id'])) {
                // Update existing variant
                $variationData = [
                    'title' => $variation['title'] ?? '',
                    'price' => $variation['price'] ?? 0,
                    'discounted_price' => $variation['special_price'] ?? 0,
                    'stock' => $variation['stock'] ?? 0,
                    'status' => $variation['status'] ?? 0,
                    'is_unlimited_stock' => $is_unlimited_stock,
                ];
                $productVariantsModel->set($variationData)
                    ->where('id', $variation['id'])
                    ->where('product_id', $product_id)
                    ->update();

                // Map index to existing variant ID
                $variantIdMap[$index] = $variation['id'];
            } else {
                // Insert new variant
                $variationData = [
                    'title' => $variation['title'] ?? '',
                    'price' => $variation['price'] ?? 0,
                    'discounted_price' => $variation['special_price'] ?? 0,
                    'stock' => $variation['stock'] ?? 0,
                    'status' => $variation['status'] ?? 0,
                    'is_unlimited_stock' => $is_unlimited_stock,
                    'product_id' => $product_id ?? null,
                ];
                $variantId = $productVariantsModel->insert($variationData);

                // Map index to new variant ID
                $variantIdMap[$index] = $variantId;
            }
        }

        // Handle variant images with variant_id - MULTIPLE IMAGES PER VARIANT
        $productImagesModel = new ProductImagesModel();

        $uploadedFiles = $this->request->getFiles();
        $variantImageIndexes = $this->request->getPost('variant_image_indexes') ?? [];

        log_message('debug', 'Uploaded Files Structure: ' . print_r($uploadedFiles, true));
        log_message('debug', 'Variant Image Indexes: ' . print_r($variantImageIndexes, true));
        log_message('debug', 'Variant ID Map: ' . print_r($variantIdMap, true));

        if (isset($uploadedFiles['variant_images']) && is_array($variantImageIndexes) && !empty($variantImageIndexes)) {
            $variantImages = $uploadedFiles['variant_images'];

            foreach ($variantImages as $key => $file) {
                if (!$file->isValid() || $file->hasMoved()) {
                    log_message('error', "Invalid file at index {$key}");
                    continue;
                }

                if (!isset($variantImageIndexes[$key])) {
                    log_message('error', "No variant index found for file at position {$key}");
                    continue;
                }

                $variantIndex = $variantImageIndexes[$key];

                if (!isset($variantIdMap[$variantIndex])) {
                    log_message('error', "Variant index '{$variantIndex}' not found in variantIdMap");
                    continue;
                }

                $variantId = $variantIdMap[$variantIndex];

                if (empty($variantId) || !is_numeric($variantId) || $variantId <= 0) {
                    log_message('error', "Invalid variant ID: {$variantId} for variant index: {$variantIndex}");
                    continue;
                }

                try {
                    $newName = $file->getRandomName();
                    $file->move('uploads/product/', $newName);
                    $db_file_path = 'uploads/product/' . $newName;

                    $variantImageData = [
                        'product_id' => $product_id,
                        'product_variant_id' => $variantId,
                        'image' => $db_file_path,
                    ];

                    $insertResult = $productImagesModel->insert($variantImageData);

                    if (!$insertResult) {
                        log_message('error', "Failed to insert variant image. Data: " . print_r($variantImageData, true));
                    } else {
                        log_message('debug', "Successfully inserted image for variant ID: {$variantId}");
                    }

                } catch (\Exception $e) {
                    log_message('error', "Exception while uploading variant image: " . $e->getMessage());
                    continue;
                }
            }
        } else {
            log_message('debug', 'No new variant images uploaded');
        }

        $output['success'] = true;
        $output['message'] = 'Product updated successfully';

        return $this->response->setJSON($output);
    }

    public function deleteVariantImage()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            $output['message'] = 'Unauthorized';
            return $this->response->setJSON($output);
        }

        if (!can_delete('product')) {
            $output['message'] = 'Permission not allowed';
            return $this->response->setJSON($output);
        }

        if ($this->settings['demo_mode']) {
            $output['message'] = 'Demo Mode! Permission not allowed';
            return $this->response->setJSON($output);
        }

        $productId = $this->request->getPost('productId');
        $variantId = $this->request->getPost('variantId');
        $imageId = $this->request->getPost('imageId');

        if (empty($productId) || empty($imageId)) {
            $output['message'] = 'Invalid parameters';
            return $this->response->setJSON($output);
        }

        $productImagesModel = new ProductImagesModel();

        // Get the image record
        $image = $productImagesModel
            ->where('id', $imageId)
            ->where('product_id', $productId)
            ->first();

        if (!$image) {
            $output['message'] = 'Image not found';
            return $this->response->setJSON($output);
        }

        // Delete the physical file
        if (file_exists($image['image'])) {
            unlink($image['image']);
        }

        // Delete the database record
        $deleted = $productImagesModel->delete($imageId);

        if ($deleted) {
            $output['success'] = true;
            $output['message'] = 'Variant image deleted successfully';
        } else {
            $output['message'] = 'Failed to delete variant image';
        }

        return $this->response->setJSON($output);
    }

    public function bulkImport()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('product-bulk-import')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();

            return view('product/bulkImport', $data);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }
    public function bulkImportFile()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_add('product-bulk-import')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $file = $this->request->getFile('import_file');

        if ($file->isValid() && !$file->hasMoved()) {
            $csvFile = fopen($file->getTempName(), 'r');

            // Skip header row
            fgetcsv($csvFile);

            $productModel             = new ProductModel();
            $productVariantsModel     = new ProductVariantsModel();
            $productTagModel          = new ProductTagModel();
            $tagsModel                = new TagsModel();
            $productCategoryModel     = new ProductCategoryModel();
            $productSubcategoryModel  = new ProductSubcategoryModel();
            $productTaxModel          = new ProductTaxModel();

            $tagCache = [];

            // CSV column layout (0-based):
            // 0  Product Name
            // 1  Brand ID
            // 2  Seller ID
            // 3  Categories (pipe-separated IDs)
            // 4  Subcategories (pipe-separated IDs)
            // 5  Description
            // 6  Status
            // 7  Popular
            // 8  Deal of the Day
            // 9  Tax IDs (pipe-separated)
            // 10 Tax Included In Price
            // 11 Manufacturer
            // 12 Made In
            // 13 Is Returnable
            // 14 Return Days
            // 15 Total Allowed Quantity
            // 16 FSSAI License No
            // 17 SEO Title
            // 18 SEO Keywords
            // 19 SEO Alt Text
            // 20 SEO Description
            // 21 Tags (pipe-separated)
            // 22+ Variants: Title, Price, Discounted Price, Stock, Status (5 cols each)

            while (($row = fgetcsv($csvFile, 2000, ',')) !== false) {
                if (empty($row[0])) continue;

                $slug_prev = str_replace(" ", "-", $row[0]);
                $slug      = preg_replace('/[^A-Za-z0-9-]/', '', strtolower($slug_prev));
                $slug1     = $slug;
                $x         = 1;
                while ($productModel->where('slug', $slug1)->countAllResults() > 0) {
                    $slug1 = $slug . $x++;
                }

                $product = [
                    'product_name'          => $row[0],
                    'brand_id'              => $row[1] ?? '',
                    'seller_id'             => $row[2] ?? 0,
                    'slug'                  => $slug1,
                    'description'           => $row[5] ?? '',
                    'status'                => $row[6] ?? 0,
                    'popular'               => $row[7] ?? 0,
                    'deal_of_the_day'       => $row[8] ?? 0,
                    'tax_included_in_price' => $row[10] ?? 0,
                    'manufacturer'          => $row[11] ?? '',
                    'made_in'               => $row[12] ?? '',
                    'is_returnable'         => $row[13] ?? 0,
                    'return_days'           => $row[14] ?? 0,
                    'total_allowed_quantity'=> $row[15] ?? 0,
                    'fssai_lic_no'          => $row[16] ?? '',
                    'seo_title'             => $row[17] ?? '',
                    'seo_keywords'          => $row[18] ?? '',
                    'seo_alt_text'          => $row[19] ?? '',
                    'seo_description'       => $row[20] ?? '',
                    'date'                  => date('Y-m-d H:i:s'),
                ];

                $productModel->insert($product);
                $productId = $productModel->insertID();

                // ===== Categories (col 3, pipe-separated) =====
                $categoryIds = array_filter(array_map('trim', explode('|', $row[3] ?? '')));
                if (!empty($categoryIds)) {
                    $catData = [];
                    foreach ($categoryIds as $catId) {
                        if ($catId) $catData[] = ['product_id' => $productId, 'category_id' => $catId];
                    }
                    if (!empty($catData)) $productCategoryModel->insertBatch($catData);
                }

                // ===== Subcategories (col 4, pipe-separated) =====
                $subcategoryIds = array_filter(array_map('trim', explode('|', $row[4] ?? '')));
                if (!empty($subcategoryIds)) {
                    $subData = [];
                    foreach ($subcategoryIds as $subId) {
                        if ($subId) $subData[] = ['product_id' => $productId, 'subcategory_id' => $subId];
                    }
                    if (!empty($subData)) $productSubcategoryModel->insertBatch($subData);
                }

                // ===== Taxes (col 9, pipe-separated) =====
                $taxIds = array_filter(array_map('trim', explode('|', $row[9] ?? '')));
                if (!empty($taxIds)) {
                    $productTaxModel->syncProductTaxes($productId, $taxIds);
                }

                // ===== Tags (col 21, pipe-separated) =====
                $tagData = [];
                $tags = array_filter(array_map('trim', explode('|', $row[21] ?? '')));
                foreach ($tags as $tagName) {
                    if (empty($tagName)) continue;
                    if (!isset($tagCache[$tagName])) {
                        $existing = $tagsModel->where('name', $tagName)->first();
                        if ($existing) {
                            $tagCache[$tagName] = $existing['id'];
                        } else {
                            $tagsModel->insert(['name' => $tagName]);
                            $tagCache[$tagName] = $tagsModel->insertID();
                        }
                    }
                    $tagData[] = ['product_id' => $productId, 'tag_id' => $tagCache[$tagName]];
                }
                if (!empty($tagData)) {
                    $productTagModel->insertBatch($tagData);
                }

                // ===== Variants (col 22+, 5 cols each: Title, Price, Discounted Price, Stock, Status) =====
                $variantData = [];
                for ($i = 22; $i < count($row); $i += 5) {
                    if (isset($row[$i]) && $row[$i] !== '') {
                        $stock = $row[$i + 3] ?? '';
                        $variantData[] = [
                            'product_id'       => $productId,
                            'title'            => $row[$i],
                            'price'            => $row[$i + 1] ?? 0,
                            'discounted_price' => $row[$i + 2] ?? 0,
                            'stock'            => $stock,
                            'status'           => $row[$i + 4] ?? 0,
                            'is_unlimited_stock' => ($stock === '') ? 1 : 0,
                        ];
                    }
                }
                if (!empty($variantData)) {
                    $productVariantsModel->insertBatch($variantData);
                }
            }

            fclose($csvFile);
            session()->setFlashdata('success', 'Bulk imported successfully');
            return redirect()->back();
        }

        session()->setFlashdata('error', 'Error occurred during file upload or data import.');
        return redirect()->back();
    }
    public function exportProductsInCSV()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_view('product-bulk-update')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $productModel            = new ProductModel();
        $productVariantsModel    = new ProductVariantsModel();
        $productTagModel         = new ProductTagModel();
        $tagsModel               = new TagsModel();
        $productCategoryModel    = new ProductCategoryModel();
        $productSubcategoryModel = new ProductSubcategoryModel();
        $productTaxModel         = new ProductTaxModel();

        $products = $productModel->where('is_delete', 0)->findAll();

        $output = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="bulk_update_products.csv"');

        // Column layout matches bulkImportFile() and bulkUpdateFile():
        // 0  Product ID
        // 1  Product Name
        // 2  Brand ID
        // 3  Seller ID
        // 4  Categories (pipe-separated IDs)
        // 5  Subcategories (pipe-separated IDs)
        // 6  Description
        // 7  Status
        // 8  Popular
        // 9  Deal of the Day
        // 10 Tax IDs (pipe-separated)
        // 11 Tax Included In Price
        // 12 Manufacturer
        // 13 Made In
        // 14 Is Returnable
        // 15 Return Days
        // 16 Total Allowed Quantity
        // 17 FSSAI License No
        // 18 SEO Title
        // 19 SEO Keywords
        // 20 SEO Alt Text
        // 21 SEO Description
        // 22 Tags (pipe-separated)
        // 23+ Variants: Variant ID, Title, Price, Discounted Price, Stock, Status (6 cols each)

        $headers = [
            'Product ID',
            'Product Name',
            'Brand ID',
            'Seller ID',
            'Category IDs (pipe-separated)',
            'Subcategory IDs (pipe-separated)',
            'Description',
            'Status',
            'Popular',
            'Deal of the Day',
            'Tax IDs (pipe-separated)',
            'Tax Included In Price',
            'Manufacturer',
            'Made In',
            'Is Returnable',
            'Return Days',
            'Total Allowed Quantity',
            'FSSAI License No',
            'SEO Title',
            'SEO Keywords',
            'SEO Alt Text',
            'SEO Description',
            'Tags (pipe-separated)',
        ];

        $variantHeaders = ['Variant ID', 'Variant Title', 'Variant Price', 'Variant Discounted Price', 'Variant Stock', 'Variant Status'];
        for ($i = 1; $i <= 5; $i++) {
            foreach ($variantHeaders as $header) {
                $headers[] = "Variant {$i} {$header}";
            }
        }

        fputcsv($output, $headers);

        foreach ($products as $product) {
            $productId = $product['id'];

            // Categories
            $catIds = $productCategoryModel->where('product_id', $productId)->findColumn('category_id') ?? [];
            $categoriesString = implode('|', $catIds);

            // Subcategories
            $subIds = $productSubcategoryModel->where('product_id', $productId)->findColumn('subcategory_id') ?? [];
            $subcategoriesString = implode('|', $subIds);

            // Taxes
            $taxIds = $productTaxModel->where('product_id', $productId)->findColumn('tax_id') ?? [];
            $taxesString = implode('|', $taxIds);

            // Tags
            $tagIds = $productTagModel->where('product_id', $productId)->findColumn('tag_id');
            $tags = $tagIds ? $tagsModel->whereIn('id', $tagIds)->findColumn('name') : [];
            $tagsString = implode('|', $tags);

            // Variants
            $variants = $productVariantsModel->where('product_id', $productId)->where('is_delete', 0)->findAll();
            $variantData = [];
            foreach ($variants as $variant) {
                $variantData[] = [
                    $variant['id'],
                    $variant['title'],
                    $variant['price'],
                    $variant['discounted_price'],
                    $variant['stock'],
                    $variant['status'],
                ];
            }

            $row = [
                $product['id'],
                $product['product_name'],
                $product['brand_id'],
                $product['seller_id'],
                $categoriesString,
                $subcategoriesString,
                $product['description'],
                $product['status'],
                $product['popular'],
                $product['deal_of_the_day'],
                $taxesString,
                $product['tax_included_in_price'],
                $product['manufacturer'],
                $product['made_in'],
                $product['is_returnable'],
                $product['return_days'],
                $product['total_allowed_quantity'],
                $product['fssai_lic_no'],
                $product['seo_title'],
                $product['seo_keywords'],
                $product['seo_alt_text'],
                $product['seo_description'],
                $tagsString,
            ];

            // Up to 5 variants (6 cols each), pad with empty if fewer
            for ($i = 0; $i < 5; $i++) {
                if (isset($variantData[$i])) {
                    $row = array_merge($row, $variantData[$i]);
                } else {
                    $row = array_merge($row, array_fill(0, 6, ''));
                }
            }

            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
    public function bulkUpdate()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_add('product-bulk-update')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();

            return view('product/bulkUpdate', $data);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }
    public function bulkUpdateFile()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_add('product-bulk-update')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $file = $this->request->getFile('import_file');

        if ($file->isValid() && !$file->hasMoved()) {
            $csvFile = fopen($file->getTempName(), 'r');

            // Skip header row
            fgetcsv($csvFile);

            $productModel            = new ProductModel();
            $productVariantsModel    = new ProductVariantsModel();
            $productTagModel         = new ProductTagModel();
            $tagsModel               = new TagsModel();
            $productCategoryModel    = new ProductCategoryModel();
            $productSubcategoryModel = new ProductSubcategoryModel();
            $productTaxModel         = new ProductTaxModel();

            // Column layout (matches exportProductsInCSV):
            // 0  Product ID
            // 1  Product Name
            // 2  Brand ID
            // 3  Seller ID
            // 4  Categories (pipe-separated IDs)
            // 5  Subcategories (pipe-separated IDs)
            // 6  Description
            // 7  Status
            // 8  Popular
            // 9  Deal of the Day
            // 10 Tax IDs (pipe-separated)
            // 11 Tax Included In Price
            // 12 Manufacturer
            // 13 Made In
            // 14 Is Returnable
            // 15 Return Days
            // 16 Total Allowed Quantity
            // 17 FSSAI License No
            // 18 SEO Title
            // 19 SEO Keywords
            // 20 SEO Alt Text
            // 21 SEO Description
            // 22 Tags (pipe-separated)
            // 23+ Variants: Variant ID, Title, Price, Discounted Price, Stock, Status (6 cols each)

            while (($row = fgetcsv($csvFile, 2000, ',')) !== false) {
                $productId = $row[0];
                if (empty($productId)) continue;

                $productData = [
                    'product_name'          => $row[1],
                    'brand_id'              => $row[2],
                    'seller_id'             => $row[3],
                    'description'           => $row[6],
                    'status'                => $row[7],
                    'popular'               => $row[8],
                    'deal_of_the_day'       => $row[9],
                    'tax_included_in_price' => $row[11],
                    'manufacturer'          => $row[12],
                    'made_in'               => $row[13],
                    'is_returnable'         => $row[14],
                    'return_days'           => $row[15],
                    'total_allowed_quantity'=> $row[16],
                    'fssai_lic_no'          => $row[17],
                    'seo_title'             => $row[18],
                    'seo_keywords'          => $row[19],
                    'seo_alt_text'          => $row[20],
                    'seo_description'       => $row[21],
                ];
                $productModel->update($productId, $productData);

                // ===== Categories (col 4, pipe-separated) =====
                $categoryIds = array_filter(array_map('trim', explode('|', $row[4] ?? '')));
                $productCategoryModel->where('product_id', $productId)->delete();
                if (!empty($categoryIds)) {
                    $catData = [];
                    foreach ($categoryIds as $catId) {
                        if ($catId) $catData[] = ['product_id' => $productId, 'category_id' => $catId];
                    }
                    if (!empty($catData)) $productCategoryModel->insertBatch($catData);
                }

                // ===== Subcategories (col 5, pipe-separated) =====
                $subcategoryIds = array_filter(array_map('trim', explode('|', $row[5] ?? '')));
                $productSubcategoryModel->where('product_id', $productId)->delete();
                if (!empty($subcategoryIds)) {
                    $subData = [];
                    foreach ($subcategoryIds as $subId) {
                        if ($subId) $subData[] = ['product_id' => $productId, 'subcategory_id' => $subId];
                    }
                    if (!empty($subData)) $productSubcategoryModel->insertBatch($subData);
                }

                // ===== Taxes (col 10, pipe-separated) =====
                $taxIds = array_filter(array_map('trim', explode('|', $row[10] ?? '')));
                $productTaxModel->syncProductTaxes($productId, $taxIds);

                // ===== Tags (col 22, pipe-separated) =====
                $tags = array_filter(array_map('trim', explode('|', $row[22] ?? '')));
                $productTagModel->where('product_id', $productId)->delete();
                foreach ($tags as $tagName) {
                    if (empty($tagName)) continue;
                    $tag = $tagsModel->where('name', $tagName)->first();
                    if (!$tag) {
                        $tagsModel->insert(['name' => $tagName]);
                        $tagId = $tagsModel->insertID();
                    } else {
                        $tagId = $tag['id'];
                    }
                    $productTagModel->insert(['product_id' => $productId, 'tag_id' => $tagId]);
                }

                // ===== Variants (col 23+, 6 cols each: ID, Title, Price, Discounted Price, Stock, Status) =====
                $variantStart   = 23;
                $variantColumns = 6;
                while (isset($row[$variantStart])) {
                    $variantId = $row[$variantStart];
                    if (empty($variantId)) {
                        $variantStart += $variantColumns;
                        continue;
                    }
                    $stock = $row[$variantStart + 4] ?? '';
                    $variantData = [
                        'title'              => $row[$variantStart + 1],
                        'price'              => $row[$variantStart + 2],
                        'discounted_price'   => $row[$variantStart + 3],
                        'stock'              => $stock,
                        'status'             => $row[$variantStart + 5],
                        'is_unlimited_stock' => ($stock === '') ? 1 : 0,
                    ];
                    $productVariantsModel->update($variantId, $variantData);
                    $variantStart += $variantColumns;
                }
            }

            fclose($csvFile);

            session()->setFlashdata('success', 'Bulk update done successfully');
            return redirect()->back();
        }

        session()->setFlashdata('error', 'Error occurred during file upload or update.');
        return redirect()->back();
    }
    public function productByCategory()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $categoryId = $this->request->getPost('cat_change');

        if ($categoryId) {
            $productModel = new ProductModel();
            $products = $productModel->getProductByCategoryId($categoryId);

            return $this->response->setJSON($products);
        } else {
            return $this->response->setJSON(['error' => 'Invalid category']);
        }
    }
    public function productOrder()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('product-order')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();
            $categoryModel = new CategoryModel();
            $categories = $categoryModel->orderBy('row_order')->findAll();

            return view('product/productOrder', [
                'settings' => $settingModel->getSettings(),
                'categories' => $categories
            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    public function productOrderUpdate()
    {
        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_edit('product-order')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $request = $this->request->getJSON();
        $order = $request->order;

        $productModel = new ProductModel();

        foreach ($order as $index => $productId) {
            // Update the row_order field in the database based on the new order
            $productModel->update($productId, ['row_order' => $index + 1]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Product order updated']);
    }

    public function deleteOtherImage()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }

        if (!can_edit('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $productId = (int) $this->request->getPost('productId');
        $id = (int) $this->request->getPost('id');


        $productImagesModel = new ProductImagesModel();
        $delete = $productImagesModel
            ->where('product_id', $productId)
            ->where('id', $id)
            ->delete();
        if ($delete) {
            $output = ['success' => true, "message" => "Image deleted successfully"];
        } else {
            $output = ['success' => false, "message" => "Something went wrong!"];
        }
        return $this->response->setJSON($output);
    }
    public function deleteVariation()
    {
        $output = ['success' => false];

        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_delete('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        // Check if product_id is set in the POST request
        if ($this->request->getPost('product_id')) {
            // Load the database connection
            $productVariantsModel = new ProductVariantsModel();
            $cartsModel = new CartsModel();
            $productId = $this->request->getPost('product_id');
            $variationId = $this->request->getPost('variation_id');
            $data =  ['is_delete' => 1];
            $update = $productVariantsModel->where('product_id', $productId)->where('id', $variationId)->set($data)->update();
            $cartsModel->where('product_id', $productId)->where('id', $variationId)->delete();
            // Check if the update was successful
            if ($update) {
                $output['success'] = true;
                $output['message'] = 'Product variation deleted successfully';
            } else {
                $output['message'] = 'Something went wrong';
            }
        }

        // Return the output as JSON
        return $this->response->setJSON($output);
    }
    public function viewProduct($productId)
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('product')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();

            $productModel = new ProductModel();
            $categoryModel = new CategoryModel();
            $brandModel = new BrandModel();
            $sellerModel = new SellerModel();
            $subcategoryModel = new SubcategoryModel();
            $productImagesModel = new ProductImagesModel();
            $productTagModel = new ProductTagModel();
            $taxModel = new TaxModel();
            $productVariantsModel = new ProductVariantsModel();
            // Fetch product details
            $product = $productModel->find($productId);

            if (!$product) {
                return "Product not found!";
            }
            $countryModel = new CountryModel();
            $country = $countryModel->where('is_active', 1)->first();
            // Fetch related details
            $category = $categoryModel->find($product['category_id']);
            $subcategory = $subcategoryModel->find($product['subcategory_id']);
            $brand = $brandModel->find($product['brand_id']);
            $seller = $sellerModel->find($product['seller_id']);
            $tags = $productTagModel
                ->select('tags.name')
                ->join('tags', 'tags.id = product_tag.tag_id')
                ->where('product_tag.product_id', $productId)
                ->findAll();
            $productImages = $productImagesModel->where('product_id', $productId)->findAll();
            if ($product['tax_id']) {
                $tax = $taxModel->find($product['tax_id']);
            } else {
                $tax = ['tax' => 'N/A', 'percentage' => 0];
            }

            $variants = $productVariantsModel->where('product_id', $productId)
                ->where('is_delete', 0) // Ensure not deleted
                ->findAll();

            return view('product/viewProduct', [
                'settings' => $settingModel->getSettings(),
                'product' => $product,
                'category' => $category,
                'subcategory' => $subcategory,
                'brand' => $brand,
                'seller' => $seller,
                'tax' => $tax,
                'tags' => $tags,
                'productImages' => $productImages,
                'variants' => $variants,
                'country' => $country
            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    public function request()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_edit('seller-request') ||  !can_view('seller-request')) {
                return redirect()->to('admin/permission-not-allowed');
            }

            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();
            $categoryModel = new CategoryModel();
            $data['categories'] = $categoryModel->getCategories();
            $sellerModel = new SellerModel();
            $data['sellers'] = $sellerModel->findAll();
            return view('/product/request', $data);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }
    public function requestList()
    {
        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('seller')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $category = $this->request->getPost('category') ?? 0;
        $seller = $this->request->getPost('seller') ?? 0;
        $stock = '';

        $productModel = new ProductModel();
        $products = $productModel->getRequestProductsList($category, $seller,  0, $stock);
        $output['data'] = [];
        $countryModel = new CountryModel();
        foreach ($products as $row) {
            foreach ($row['variants'] as $variants) {
                $img = "<a href='" . base_url($row['main_img']) . "' target='_blank'>
                    <img class='media-object round-media' src='" . base_url($row['main_img']) . "' alt='image' style='height: 60px; width: 40%'>
                </a>";
                $action = "<a data-tooltip='tooltip' title='Edit Product' href='" . base_url("admin/product/edit/{$row['id']}") . "' class='btn btn-primary-light  btn-xs'>
                    <i class='fi fi-tr-customize-edit'></i></a>
                    <a data-tooltip='tooltip' title='View Product' href='" . base_url("admin/product/view/{$row['id']}") . "' class='btn btn-warning-light  btn-xs'>
                    <i class='fi fi-tr-overview'></i></a>
                   <a type='button' data-tooltip='tooltip' title='Delete product' onclick='deleteproduct({$row['id']})' class='btn btn-danger-light btn-xs'>
                    <i class='fi fi-tr-trash-xmark'></i></a>";

                // Stock status

                // Publish status
                $publish = $row['status'] == 1 ? "<span class='badge badge-success'>Published</span> " : "<span class='badge badge-danger'>Unpublish</span> ";

                // Product badges
                $productname = $row['product_name'];
                if ($row['popular'] == 1) {
                    $productname .= " <br> <span class='badge badge-success'>popular</span>";
                }
                if ($row['deal_of_the_day'] == 1) {
                    $productname .= " <span class='badge badge-success'>Deal Of The Day</span>";
                }


                // Prepare the output data
                $output['data'][] = [
                    $row['id'],
                    $variants['id'],
                    $productname,
                    $row['store_name'],  // Category name
                    $img,
                    $row['brand'],
                    $row['category_name'],
                    $publish,
                    $action
                ];
            }
        }

        return $this->response->setJSON($output);
    }

    public function generateDescription()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_add('product')) {
                $output = ['success' => false, "message" => "Permission not allowed"];
                return $this->response->setJSON($output);
            }
            $settingModel = new SettingsModel();
            $settings = $settingModel->getSettings();

            $productName = $this->request->getPost('productname');
            $productCategory = $this->request->getPost('categoryname');
            $productBrand = $this->request->getPost('brandname');
            $categoryModel = new CategoryModel();
            $brandModel = new BrandModel();
            $category = $categoryModel->select('category_name')->where('id', $productCategory)->first();
            $brand = $brandModel->select('brand')->where('id', $productBrand)->first();
            if ($settings['chatgpt_status']) {
                $output['success'] = true;
                $output['message'] = "Description generated successfully. ";
                $output['response'] = $this->generate_product_description($productName, $category['category_name'], $brand['brand'], $settings['chatgpt_api_key']);
                return $this->response->setJSON($output);
            } else {
                $output['success'] = false;
                $output['message'] = 'Enable ChatGPT in setting';
                return $this->response->setJSON($output);
            }
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    private  function generate_product_description($product_name, $product_category, $brand, $chatgpt_key)
    {

        $url = 'https://api.openai.com/v1/chat/completions';

        $prompt = "Write an SEO-friendly product description for a grocery item named '$product_name' in the '$product_category' category and brand is '$brand'.";

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert in writing engaging product descriptions.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 250,
        ];

        $headers = [
            'Authorization: Bearer ' . $chatgpt_key,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['error']['code']) && $result['error']['code'] == 'insufficient_quota') {
            return $result['error']['message'];
        }

        return $result['choices'][0]['message']['content'] ?? 'Unable to generate description';
    }

    public function generateSeoContent()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_add('product')) {
                $output = ['success' => false, "message" => "Permission not allowed"];
                return $this->response->setJSON($output);
            }
            $settingModel = new SettingsModel();
            $settings = $settingModel->getSettings();

            $productName = $this->request->getPost('productname');
            $productCategory = $this->request->getPost('categoryname');
            $productBrand = $this->request->getPost('brandname');
            $categoryModel = new CategoryModel();
            $brandModel = new BrandModel();
            $category = $categoryModel->select('category_name')->where('id', $productCategory)->first();
            $brand = $brandModel->select('brand')->where('id', $productBrand)->first();
            if ($settings['chatgpt_status']) {
                $output['success'] = true;
                $output['message'] = "SEO generated successfully. ";
                // $output['response'] = $this->generate_product_seo($productName, $category['category_name'], $brand['brand'], $settings['chatgpt_api_key']);
                // return $this->response->setJSON($output);

                $url = 'https://api.openai.com/v1/chat/completions';
                $product_category =  $category['category_name'];
                $brand =  $brand['brand'];
                $chatgpt_key = $settings['chatgpt_api_key'];
                $prompt = "Generate SEO metadata for a product named '$productName' in the category '$product_category' and brand is '$brand'.

        Provide the response in JSON format with the following keys:
        - title: Meta title (max 60 characters)
        - description: SEO description (max 160 characters)
        - keywords: A comma-separated list of relevant keywords
        - alt_text: Alt text for product images";

                $data = [
                    "model" => "gpt-4o",
                    "messages" => [["role" => "user", "content" => $prompt]],
                    "temperature" => 0.7
                ];

                $headers = [
                    'Authorization: Bearer ' . $chatgpt_key,
                    'Content-Type: application/json',
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $response = curl_exec($ch);
                curl_close($ch);
                $result = json_decode($response, true);
                if (isset($result['error']['code'])) {
                    return $result['error']['message'];
                }


                $rawContent = $result["choices"][0]["message"]["content"] ?? '{}';

                // Clean markdown-style code blocks if any
                $cleanedContent = preg_replace('/^```(?:json)?|```$/m', '', $rawContent);

                // Now decode it
                $seoData = json_decode(trim($cleanedContent), true);

                // Check if JSON was valid
                if (json_last_error() === JSON_ERROR_NONE) {
                    $output['success'] = true;
                    $output['message'] = "SEO generated successfully.";
                    $output['response'] = $seoData;
                } else {
                    $output['success'] = false;
                    $output['message'] = "Failed to parse ChatGPT response.";
                    $output['response'] = $rawContent;
                }
                return $this->response->setJSON($output);
            } else {
                $output['success'] = false;
                $output['message'] = 'Enable ChatGPT in setting';
                return $this->response->setJSON($output);
            }
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    private  function generate_product_seo($product_name, $product_category, $brand, $chatgpt_key)
    {

        $url = 'https://api.openai.com/v1/chat/completions';

        $prompt = "Generate SEO metadata for a product named '$product_name' in the category '$product_category' and brand is '$brand'.

        Provide the response in JSON format with the following keys:
        - title: Meta title (max 60 characters)
        - description: SEO description (max 160 characters)
        - keywords: A comma-separated list of relevant keywords
        - alt_text: Alt text for product images";

        $data = [
            "model" => "gpt-4o",
            "messages" => [["role" => "user", "content" => $prompt]],
            "temperature" => 0.7
        ];

        $headers = [
            'Authorization: Bearer ' . $chatgpt_key,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);
        if (isset($result['error']['code'])) {
            return $result['error']['message'];
        }


        $seoData = json_decode($response, true)["choices"][0]["message"]["content"];

        return json_decode($seoData, true); // Decode JSON response
    }

    public function generateQrCode()
    {

        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('product')) {
                return redirect()->to('admin/permission-not-allowed');
            }

            $request = service('request');
            $product_id = $request->getPost('product_id');

            if (empty($product_id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Product Id is required']);
            }
            $productModel = new ProductModel();
            $product_name = $productModel->select('product_name, slug, main_img')->where('id', $product_id)->first();
            $writer = new PngWriter();

            // Create QR code
            $qrCode = new QrCode(
                data: $product_name['product_name'],
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Low,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );
            $logo = new Logo(
                path: $product_name['main_img'],
                resizeToWidth: 50,
                punchoutBackground: true
            );

            // Create generic label
            $label = new Label(
                text: $product_name['product_name'],
                textColor: new Color(255, 0, 0)
            );

            $result = $writer->write($qrCode, $logo, $label);
            $filename = 'qrcode.png';
            return $this->response
                ->setHeader('Content-Description', 'File Transfer')
                ->setHeader('Content-Type', 'image/png')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Content-Transfer-Encoding', 'binary')
                ->setBody($result->getString());
        }
    }
    public function rating($product_id)
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_edit('product')) {
                return redirect()->to('admin/permission-not-allowed');
            }

            $settingModel = new SettingsModel();
            $productRatingsModel = new ProductRatingsModel();
            $ratingList = $productRatingsModel->select('product_ratings.id as product_ratings_id, product.id, product_ratings.title, product_ratings.order_id, product_ratings.review, product_ratings.created_at, product_ratings.rate, user.name, product_ratings.is_approved_to_show')
                ->where('product.id', $product_id)
                ->join('product', 'product.id = product_ratings.product_id', 'left')
                ->orderBy('id', 'desc')
                ->join('user', 'user.id = product_ratings.user_id', 'left')
                ->findAll();

            $productModel = new ProductModel();
            $productInfo = $productModel->select('product_name')->where('id', $product_id)->first();

            return view('product/rating', [
                'settings' => $settingModel->getSettings(),
                'ratingLists' => $ratingList,
                'productInfo' => $productInfo
            ]);
        } else { 
            return redirect()->to('admin/auth/login');
        }
    }
    public function updateRating() 
    {
        $output = ['success' => false];

        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_edit('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        // Check if product_id is set in the POST request
        if ($this->request->getPost('id')) {
            // Load the database connection
            $productRatingsModel = new ProductRatingsModel();
            $rating_id = $this->request->getPost('id');
            $is_approved_to_show = $this->request->getPost('status');
            $data =  ['is_approved_to_show' => $is_approved_to_show];
            $update = $productRatingsModel->where('id', $rating_id)->set($data)->update();

            if ($update) {
                $output['success'] = true;
                $output['message'] = 'Review updated successfully';
            } else {
                $output['message'] = 'Something went wrong';
            }
        }

        // Return the output as JSON
        return $this->response->setJSON($output);
    }

    public function copyProductFromSellerIndex()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('product-order')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();
            $sellerModel = new SellerModel();
            $sellers = $sellerModel->findAll();

            return view('product/copyProductFromSeller', [
                'settings' => $settingModel->getSettings(),
                'sellers' => $sellers
            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    public function getCategoryBySeller()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $seller_id = $this->request->getPost('seller_id');

        if ($seller_id) {
            $sellerCategoriesModel = new SellerCategoriesModel();
            $products = $sellerCategoriesModel->getCategoryBySeller($seller_id);

            return $this->response->setJSON($products);
        } else {
            return $this->response->setJSON(['error' => 'Invalid category']);
        }
    }
    public function getSubcategoryByCategory()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $category_id = $this->request->getPost('category_id');

        if ($category_id) {
            $subcategoryModel = new SubcategoryModel();
            $subcategory = $subcategoryModel->getSubcategoriesByCategoryId($category_id);

            return $this->response->setJSON($subcategory);
        } else {
            return $this->response->setJSON(['error' => 'Invalid category']);
        }
    }
    public function getProductBySellerCategorySubcategory()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $subcategory_id = $this->request->getPost('subcategory_id');

        if ($subcategory_id) {
            $category_id = $this->request->getPost('category_id');
            $seller_id = $this->request->getPost('seller_id');
            $productModel = new ProductModel();
            $to_seller_id = $this->request->getPost('to_seller_id');
            $products['item'] = $productModel->select('id, product_name, main_img')
                ->where([
                    'category_id' => $category_id,
                    'subcategory_id' => $subcategory_id,
                    'seller_id' => $seller_id,
                    'is_delete' => 0
                ])->findAll();

            // Get existing product names for the to_seller
            $existingProducts = $productModel->select('product_name')
                ->where('seller_id', $to_seller_id)
                ->where('is_delete', 0)
                ->findAll();

            $existingNames = array_column($existingProducts, 'product_name');

            // Add "exists" flag to each product
            foreach ($products['item'] as &$product) {
                $product['exists'] = in_array($product['product_name'], $existingNames); // You can use 'slug' if preferred
            }

            $products['base_url'] = base_url();

            return $this->response->setJSON($products);
        } else {
            return $this->response->setJSON(['error' => 'Invalid category']);
        }
    }
    public function copySelectedProducts()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_view('product')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $product_ids = $this->request->getPost('product_ids');
        $to_seller_id = $this->request->getPost('to_seller_id');

        if (!$product_ids || !$to_seller_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing data']);
        }

        $productModel = new ProductModel();
        $variantModel = new ProductVariantsModel(); // Optional, if you copy variants too
        $productTagModel = new ProductTagModel();
        $productImagesModel = new ProductImagesModel();

        foreach ($product_ids as $product_id) {
            $product = $productModel->find($product_id);

            if ($product) {
                unset($product['id']); // Remove ID to insert new
                $product['seller_id'] = $to_seller_id;
                $product['created_at'] = date('Y-m-d H:i:s');
                $product['updated_at'] = date('Y-m-d H:i:s');

                $slug_prev = str_replace(" ", "-", $product['product_name']);
                $slug = preg_replace('/[^A-Za-z0-9-]/', '', strtolower($slug_prev));
                $product['slug'] = $slug;
                $check = true;
                $x = 1;
                while ($check) {
                    $duplicateSlug = $productModel->where('slug', $product['slug'])->countAllResults();
                    if ($duplicateSlug > 0) {
                        $product['slug'] = $slug . $x;
                    } else {
                        $check = false;
                    }
                    $x++;
                }


                $newProductId = $productModel->insert($product);

                $variants = $variantModel->where('product_id', $product_id)->findAll();
                foreach ($variants as $variant) {
                    unset($variant['id']);
                    $variant['product_id'] = $newProductId;
                    $variantModel->insert($variant);
                }

                $productTags = $productTagModel->where('product_id', $product_id)->findAll();
                foreach ($productTags as $productTag) {
                    unset($productTag['id']);
                    $productTag['product_id'] = $newProductId;
                    $productTagModel->insert($productTag);
                }

                $productImages = $productImagesModel->where('product_id', $product_id)->findAll();
                foreach ($productImages as $productImage) {
                    unset($productImage['id']);
                    $productImage['product_id'] = $newProductId;
                    $productImagesModel->insert($productImage);
                }
            }
        }

        return $this->response->setJSON(['success' => true]);
    }
}
