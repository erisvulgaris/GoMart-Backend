<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\HomeScreenModel;
use App\Models\SettingsModel;

class HomeScreen extends BaseController
{
    private function uploadScreenFile($fileKey, $oldPath = null)
    {
        $file = $this->request->getFile($fileKey);
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null; // no new file uploaded
        }

        $uploadDir = FCPATH . 'uploads/home_screens/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Delete old file if replacing
        if ($oldPath && file_exists(FCPATH . $oldPath)) {
            @unlink(FCPATH . $oldPath);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);
        return 'uploads/home_screens/' . $newName;
    }

    public function index()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Admin') {
            if (!can_view('home-section')) {
                return redirect()->to('admin/permission-not-allowed');
            }
            $settingModel = new SettingsModel();
            return view('homeScreen/index', [
                'settings' => $settingModel->getSettings(),
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
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $homeScreenModel = new HomeScreenModel();
        $screens = $homeScreenModel->getAllHomeScreens();
        $baseUrl = base_url();

        $output = ['data' => []];
        $x = 1;
        foreach ($screens as $row) {
            $defaultBadge = $row['is_default'] == 1
                ? "<span class='badge badge-success'>Default</span>"
                : "";

            $statusBadge = $row['status'] == 1
                ? "<span class='badge badge-success'>Active</span>"
                : "<span class='badge badge-danger'>Hidden</span>";

            // Appearance preview
            if ($row['header_type'] === 'gif' && !empty($row['header_gif'])) {
                $appearance = "<img src='{$baseUrl}{$row['header_gif']}' style='height:24px;width:48px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6'>";
            } else {
                $s = $row['gradient_start'] ?: '#56ab2f';
                $e = $row['gradient_end']   ?: '#a8e063';
                $appearance = "<span style='display:inline-block;width:48px;height:24px;border-radius:4px;background:linear-gradient(90deg,{$s},{$e});border:1px solid #dee2e6'></span>";
            }

            // Tab icon preview
            $tabPreview = !empty($row['tab_icon'])
                ? "<img src='{$baseUrl}{$row['tab_icon']}' style='height:20px;width:20px;object-fit:contain;'>"
                : "<span class='text-muted'>–</span>";

            $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
            $action = "<a data-tooltip='tooltip' title='Edit Screen' href='javascript:void(0)' onclick='editScreen(this)' data-row='{$rowJson}' class='btn btn-primary-light btn-xs'><i class='fi fi-tr-customize-edit'></i></a>";

            if ($row['is_default'] != 1) {
                $action .= " <a type='button' data-tooltip='tooltip' title='Delete Screen' onclick='deleteScreen({$row['id']})' class='btn btn-danger-light btn-xs'><i class='fi fi-tr-trash-xmark'></i></a>";
            }

            $output['data'][] = [
                $x,
                esc($row['name']),
                esc($row['slug']),
                $appearance,
                $tabPreview,
                $defaultBadge,
                $statusBadge,
                $row['sort_order'],
                $action,
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
        if (!can_add('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');

        if (empty($name)) {
            $output['message'] = "Name is required";
            return $this->response->setJSON($output);
        }

        $slug = url_title($slug ?: $name, '-', true);

        $homeScreenModel = new HomeScreenModel();

        $existing = $homeScreenModel->where('slug', $slug)->first();
        if ($existing) {
            $output['message'] = "Slug already exists. Please use a different name.";
            return $this->response->setJSON($output);
        }

        $headerType = $this->request->getPost('header_type') ?: 'gradient';

        $data = [
            'name'               => $name,
            'slug'               => $slug,
            'is_default'         => 0,
            'status'             => (int)($this->request->getPost('status') ?? 1),
            'sort_order'         => (int)($this->request->getPost('sort_order') ?? 0),
            'header_type'        => $headerType,
            'gradient_start'      => $this->request->getPost('gradient_start') ?: null,
            'gradient_end'        => $this->request->getPost('gradient_end') ?: null,
            'overlay_text_color'  => $this->request->getPost('overlay_text_color') ?: null,
            'tab_active_color'    => $this->request->getPost('tab_active_color') ?: null,
            'tab_inactive_color'  => $this->request->getPost('tab_inactive_color') ?: null,
        ];

        $gifPath = $this->uploadScreenFile('header_gif');
        if ($gifPath) $data['header_gif'] = $gifPath;

        $iconPath = $this->uploadScreenFile('tab_icon');
        if ($iconPath) $data['tab_icon'] = $iconPath;

        if ($homeScreenModel->insertHomeScreen($data)) {
            $output['success'] = true;
            $output['message'] = "Home screen added successfully!";
        } else {
            $output['message'] = "Unable to add home screen";
        }

        return $this->response->setJSON($output);
    }

    public function update()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_edit('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $id   = $this->request->getPost('id');
        $name = $this->request->getPost('name');

        if (empty($id) || empty($name)) {
            $output['message'] = "Invalid data";
            return $this->response->setJSON($output);
        }

        $homeScreenModel = new HomeScreenModel();
        $existing = $homeScreenModel->find($id);
        if (!$existing) {
            $output['message'] = "Screen not found";
            return $this->response->setJSON($output);
        }

        $slug = url_title($this->request->getPost('slug') ?: $name, '-', true);
        $slugConflict = $homeScreenModel->where('slug', $slug)->where('id !=', $id)->first();
        if ($slugConflict) {
            $output['message'] = "Slug already exists.";
            return $this->response->setJSON($output);
        }

        $headerType = $this->request->getPost('header_type') ?: 'gradient';

        $data = [
            'name'               => $name,
            'slug'               => $slug,
            'status'             => (int)($this->request->getPost('status') ?? 1),
            'sort_order'         => (int)($this->request->getPost('sort_order') ?? 0),
            'header_type'        => $headerType,
            'gradient_start'      => $this->request->getPost('gradient_start') ?: null,
            'gradient_end'        => $this->request->getPost('gradient_end') ?: null,
            'overlay_text_color'  => $this->request->getPost('overlay_text_color') ?: null,
            'tab_active_color'    => $this->request->getPost('tab_active_color') ?: null,
            'tab_inactive_color'  => $this->request->getPost('tab_inactive_color') ?: null,
        ];

        // Handle header_gif
        if ($this->request->getPost('clear_header_gif') == '1') {
            if (!empty($existing['header_gif']) && file_exists(FCPATH . $existing['header_gif'])) {
                @unlink(FCPATH . $existing['header_gif']);
            }
            $data['header_gif'] = null;
        } else {
            $gifPath = $this->uploadScreenFile('header_gif', $existing['header_gif'] ?? null);
            if ($gifPath) $data['header_gif'] = $gifPath;
        }

        // Handle tab_icon
        if ($this->request->getPost('clear_tab_icon') == '1') {
            if (!empty($existing['tab_icon']) && file_exists(FCPATH . $existing['tab_icon'])) {
                @unlink(FCPATH . $existing['tab_icon']);
            }
            $data['tab_icon'] = null;
        } else {
            $iconPath = $this->uploadScreenFile('tab_icon', $existing['tab_icon'] ?? null);
            if ($iconPath) $data['tab_icon'] = $iconPath;
        }

        if ($homeScreenModel->where('id', $id)->set($data)->update()) {
            $output['success'] = true;
            $output['message'] = 'Home screen updated.';
        } else {
            $output['message'] = 'Database error occurred.';
        }

        return $this->response->setJSON($output);
    }

    public function delete()
    {
        $output = ['success' => false];

        if (!session()->has('user_id') || session('account_type') != 'Admin') {
            return redirect()->to('admin/login');
        }
        if (!can_delete('home-section')) {
            $output = ['success' => false, "message" => "Permission not allowed"];
            return $this->response->setJSON($output);
        }
        if ($this->settings['demo_mode']) {
            $output = ['success' => false, "message" => "Demo Mode! Permission not allowed"];
            return $this->response->setJSON($output);
        }

        $id = $this->request->getPost('screen_id');
        if ($id) {
            $homeScreenModel = new HomeScreenModel();
            $screen = $homeScreenModel->find($id);

            if ($screen && $screen['is_default'] == 1) {
                $output['message'] = "Cannot delete the default Home screen.";
                return $this->response->setJSON($output);
            }

            // Clean up uploaded files
            if (!empty($screen['header_gif']) && file_exists(FCPATH . $screen['header_gif'])) {
                @unlink(FCPATH . $screen['header_gif']);
            }
            if (!empty($screen['tab_icon']) && file_exists(FCPATH . $screen['tab_icon'])) {
                @unlink(FCPATH . $screen['tab_icon']);
            }

            if ($homeScreenModel->deleteHomeScreen($id)) {
                $output['success'] = true;
                $output['message'] = "Home screen deleted successfully!";
            } else {
                $output['message'] = "Something went wrong";
            }
        }

        return $this->response->setJSON($output);
    }
}
