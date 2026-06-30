<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\BannerModel;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\HomeScreenModel;
use App\Models\SellerModel;
use App\Models\SettingsModel;

class Banner extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('banner')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            $categoryModel = new CategoryModel();
            $homeScreenModel = new HomeScreenModel();
            $brandModel = new BrandModel();
            $storeModel = new SellerModel();

            return view('/banner/add', [
                'settings'    => $settingModel->getSettings(),
                'categories'  => $categoryModel->getCategories(),
                'homeScreens' => $homeScreenModel->getAllHomeScreens(),
                'brands'      => $brandModel->getBrandList(),
                'stores'      => $storeModel->where('status', 1)->where('is_delete', 0)->findAll(),

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
        if (!can_view('banner')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $homeScreenId = $this->request->getPost('home_screen_id');
        $bannerModel = new BannerModel();

        if (!empty($homeScreenId)) {
            $banners = $bannerModel->getBannersByHomeScreen($homeScreenId);
        } else {
            $banners = $bannerModel->orderBy('sort_order', 'ASC')->findAll();
        }

        $output = ['data' => []];
        $x = 1;
        foreach ($banners as $banner) {
            $img = "<a href='" . base_url($banner['image']) . "' target='_blank'><img class='media-object round-media' src='" . base_url($banner['image']) . "' alt='image' style='height: 75px;width:40%'></a>";

            $bannerTypeBadge = "<span class='badge badge-info'>" . esc($banner['banner_type']) . "</span>";

            $statusBadge = $banner['status'] == 1
                ? "<span class='badge badge-success'>Active</span>"
                : "<span class='badge badge-danger'>Hidden</span>";

            $action = "<a data-tooltip='tooltip' title='Edit Banner' href='" . base_url('/admin/banner/edit/' . $banner['id']) . "' class='btn btn-primary-light btn-xs'><i class='fi fi-tr-customize-edit'></i></a>
                       <a type='button' data-tooltip='tooltip' title='Delete Banner' onclick='deletebanner({$banner['id']})' class='btn btn-danger-light btn-xs'><i class='fi fi-tr-trash-xmark'></i></a>";
            
                       if($banner['placement'] == 0){
                        $placementBadge = "<span class='badge badge-secondary'>Header</span>";
                       }elseif($banner['placement'] == 1){
                        $placementBadge = "<span class='badge badge-success'>Deal of the Day</span>";
                       }elseif($banner['placement'] == 2){  
                        $placementBadge = "<span class='badge badge-primary'>Home</span>";
                       }elseif($banner['placement'] == 3){
                        $placementBadge = "<span class='badge badge-danger'>Footer</span>";
                       }
            $output['data'][] = [
                $x,
                $bannerTypeBadge,
                $img,
                $statusBadge." ".$placementBadge,
                $action
            ];
            $x++;
        }

        return $this->response->setJSON($output);
    }

    public function add()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_add('banner')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $rules = [
            'banner_type'    => 'required',
            'home_screen_id' => 'required|integer',
            'banner_img'     => 'required',
            'placement'      => 'required|in_list[0,1,2,3]',
        ];

        if ($this->validate($rules)) {
            $homeScreenId = $this->request->getPost('home_screen_id');
            $bannerType = $this->request->getPost('banner_type');
            $contentId = $this->request->getPost('content_id');
            $redirectUrl = $this->request->getPost('redirect_url');

            $bannerImg = $this->request->getPost('banner_img');
            list(, $bannerImg) = explode(';', $bannerImg);
            list(, $bannerImg) = explode(',', $bannerImg);
            $bannerImg = base64_decode($bannerImg);

            $db_file_path = 'uploads/banner/banner_' . time() . '.webp';
            $full_file_path = FCPATH . $db_file_path;

            if (!is_dir(dirname($full_file_path))) {
                mkdir(dirname($full_file_path), 0777, true);
            }

            if (file_put_contents($full_file_path, $bannerImg)) {
                $bannerModel = new BannerModel();
                $data = [
                    'home_screen_id' => (int)$homeScreenId,
                    'banner_type'    => $bannerType,
                    'content_id'     => !empty($contentId) ? (int)$contentId : null,
                    'redirect_url'   => $redirectUrl ?: null,
                    'image'          => $db_file_path,
                    'status'         => (int)($this->request->getPost('status') ?? 1),
                    'sort_order'     => (int)($this->request->getPost('sort_order') ?? 0),
                    'placement'      => (int)$this->request->getPost('placement'),
                ];

                if ($bannerModel->insertBanner($data)) {
                    $output['success'] = true;
                    $output['message'] = "Banner added";
                } else {
                    $output['message'] = "Unable to add banner";
                }
            }
        } else {
            $output['message'] = "Entered data is not in correct format";
        }

        return $this->response->setJSON($output);
    }

    public function delete()
    {
        $output = ['success' => false];
        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_delete('banner')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $bannerId = $this->request->getPost('ban_id');
        if ($bannerId) {
            $bannerModel = new BannerModel();
            if ($bannerModel->deleteBanner($bannerId)) {
                $output['success'] = true;
            }
        }

        return $this->response->setJSON($output);
    }

    public function edit($id)
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_edit('banner')) {
                $output = ['success' => false, "message" => "Permission not allowed"];
                return $this->response->setJSON($output);
            }
            $settingModel = new SettingsModel();
            $bannerModel = new BannerModel();
            $banner = $bannerModel->find($id);
            $categoryModel = new CategoryModel();
            $homeScreenModel = new HomeScreenModel();
            $brandModel = new BrandModel();
            $storeModel = new SellerModel();

            return view('banner/edit', [
                'settings'    => $settingModel->getSettings(),
                'banner'      => $banner,
                'categories'  => $categoryModel->getCategories(),
                'homeScreens' => $homeScreenModel->getAllHomeScreens(),
                'brands'      => $brandModel->getBrandList(),
                'stores'      => $storeModel->where('status', 1)->where('is_delete', 0)->findAll(),
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
        if (!can_edit('banner')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $banner_id = $this->request->getPost('banner_id');
        $home_screen_id = $this->request->getPost('home_screen_id');
        $banner_type = $this->request->getPost('banner_type');
        $content_id = $this->request->getPost('content_id');
        $redirect_url = $this->request->getPost('redirect_url');
        $banner_img = $this->request->getPost('banner_img');

        $status = $this->request->getPost('status');
        $sort_order = $this->request->getPost('sort_order');

        $data = [
            'home_screen_id' => (int)$home_screen_id,
            'banner_type'    => $banner_type,
            'content_id'     => !empty($content_id) ? (int)$content_id : null,
            'redirect_url'   => $redirect_url ?: null,
            'status'         => (int)($status ?? 1),
            'sort_order'     => (int)($sort_order ?? 0),
            'placement'      => (int)$this->request->getPost('placement'),
        ];

        if (!empty($banner_img)) {
            list(, $banner_img) = explode(';', $banner_img);
            list(, $banner_img) = explode(',', $banner_img);
            $banner_img = base64_decode($banner_img);

            $db_file_path = 'uploads/banner/banner_' . time() . '.webp';
            $a_file_path = FCPATH . $db_file_path;
            file_put_contents($a_file_path, $banner_img);

            $data['image'] = $db_file_path;
        }

        $bannerModel = new BannerModel();

        if ($bannerModel->where('id', $banner_id)->set($data)->update()) {
            $output['success'] = true;
            $output['message'] = 'Banner updated.';
        } else {
            $output['message'] = 'Database error occurred.';
        }

        return $this->response->setJSON($output);
    }

    public function searchProducts()
    {
        $keyword = $this->request->getGet('q') ?? $this->request->getPost('q');
        if (empty($keyword) || strlen($keyword) < 2) {
            return $this->response->setJSON([]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('product');
        $builder->select('product.id, product.product_name as text, product.main_img');
        $builder->like('product.product_name', $keyword);
        $builder->where('product.is_delete', 0);
        $builder->where('product.status', 1);
        $builder->limit(20);

        return $this->response->setJSON($builder->get()->getResultArray());
    }
}
