<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\CategoryModel;
use App\Models\HomeSectionModel;
use App\Models\SettingsModel;
use App\Models\SubcategoryModel;
use App\Models\CityModel;
use App\Models\DeliverableAreaModel;

class HomeSection extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('home-section')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();
            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getCategories();
            $cityModel = new CityModel();
            
            return view('homeSection/add', [
                'settings' => $settingModel->getSettings(),
                'categories' => $categories,
                'city' => $cityModel->getAllCity()
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
        if (!can_view('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        

        $homeModel = new HomeSectionModel();
        $homes = $homeModel->getAllHomeData();

        $output = ['data' => []];
        $x = 1;

        foreach ($homes as $row) {
            $action = "<a data-tooltip='tooltip' title='Edit Section' href='" . site_url('admin/home_section/edit/' . $row['id']) . "' class='btn btn-primary-light  btn-xs'><i class='fi fi-tr-customize-edit'></i></a> 
                       <a type='button' data-tooltip='tooltip' title='Delete Section' onclick='deletesection(" . $row['id'] . ")' class='btn btn-danger-light btn-xs'><i class='fi fi-tr-trash-xmark'></i></a>";

            $status = $row['is_active'] == 1 ? "<span class='badge badge-success'>Published</span>" : "<span class='badge badge-danger'>Unpublish</span>";

            $output['data'][] = [
                $x,
                $row['title'],
                $row['category_name'],
                $row['subcategory_name'],
                $status,
                $action
            ];
            $x++;
        }

        return $this->response->setJSON($output);
    }

    // Method for deleting a section
    public function delete()
    {
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_delete('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }
        $output['success'] = false;

        if ($this->request->getPost('sec_id')) {
            $sec_id = $this->request->getPost('sec_id');
            $HomeSectionModel = new HomeSectionModel();
            if ($HomeSectionModel->delete($sec_id)) {
                $output['success'] = true;
                $output['message'] = "Home section deleted successfully!";
            } else {
                $output['message'] = "Something went wrong";
            }
        }
        return $this->response->setJSON($output);
    }

    public function subcategory()
    {
        // Check if the user is logged in
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        $subcategoryModel = new SubcategoryModel();

        // Retrieve the category ID from the POST request
        $catChange = $this->request->getPost('cat_change');

        // Sanitize and validate the category ID
        if ($catChange && is_numeric($catChange)) {
            // Fetch subcategories by category ID
            $subcategories = $subcategoryModel->getSubcategoriesByCategoryId($catChange);

            // Return JSON response
            return $this->response->setJSON($subcategories);
        } else {
            // Invalid request handling
            return $this->response->setJSON(['error' => 'Invalid category ID']);
        }
    }
    public function add()
    {
        $response = ['success' => false];
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }
        if (!can_add('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }
        // Get and validate POST data
        $title = $this->request->getPost('title');
        $categoryId = $this->request->getPost('category');
        $subCategoryId = $this->request->getPost('sub_category');
        $status = $this->request->getPost('status');
        $city_id = $this->request->getPost('city_id');
        $deliverable_area_id = $this->request->getPost('deliverable_area_id');
        $sort_by = $this->request->getPost('sort_by');
        $product_show_limit = $this->request->getPost('product_show_limit');
        // Prepare data for insertion
        $data = [
            'title' => $title,
            'category_id' => (int)$categoryId,
            'subcategory_id' => (int)$subCategoryId,
            'is_active' => (int)$status,
            'city_id' => $city_id,
            'deliverable_area_id' => $deliverable_area_id,
            'sort_by' => $sort_by,
            'product_show_limit' => $product_show_limit
        ];

        // Load the model
        $homeModel = new HomeSectionModel();

        // Insert data into the database
        if ($homeModel->insertHome($data)) {
            $response['success'] = true;
        }

        // Return JSON response
        return $this->response->setJSON($response);
    }

    public function edit($id)
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_edit('home-section')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $data['settings'] = $settingModel->getSettings();
            $HomeSectionModel = new HomeSectionModel();
            $homeSection = $HomeSectionModel->find($id);
            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getCategories();
            $subcategoryModel = new SubcategoryModel();

            if ($homeSection['category_id'] == 0) {
                $subcategories = [];
            } else {
                $subcategories = $subcategoryModel->where('category_id', $homeSection['category_id'])->findAll();
            }
            $cityModel = new CityModel();
            $city = $cityModel->getAllCity();
            $DeliverableAreaModel = new DeliverableAreaModel();
            $deliverable_area = $DeliverableAreaModel->where('id', $homeSection['deliverable_area_id'])->find();

            return view('homeSection/edit', [
                'settings' => $settingModel->getSettings(),
                'homeSection' => $homeSection,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'city' => $city,
                'deliverable_area'=> $deliverable_area

            ]);
        } else {
            return redirect()->to('admin/auth/login');
        }
    }

    public function update()
    {
        $output = ['success' => false];
        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login'); // Redirect to login if session is not set
        }

        if (!can_edit('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }
        // Get POST data
        $title = $this->request->getPost('title');
        $home_section_id = $this->request->getPost('home_section_id');
        $category = $this->request->getPost('category');
        $sub_category = $this->request->getPost('sub_category');
        $status = $this->request->getPost('status');
        $city_id = $this->request->getPost('city_id');
        $deliverable_area_id = $this->request->getPost('deliverable_area_id');
        $sort_by = $this->request->getPost('sort_by');
        $product_show_limit = $this->request->getPost('product_show_limit');


        $data = [
            'title' => $title,
            'category_id'  => $category,
            'subcategory_id'  => $sub_category,
            'is_active'  => $status,
            'city_id' => $city_id,
            'deliverable_area_id' => $deliverable_area_id,
            'sort_by' => $sort_by,
            'product_show_limit' => $product_show_limit
        ];
        $homeSectionModel = new HomeSectionModel();


        if ($homeSectionModel->where('id', $home_section_id)->set($data)->update()) {
            $output['success'] = true;
        } else {
            $output['message'] = 'Database error occurred.';
        }

        return $this->response->setJSON($output);
    }
}
