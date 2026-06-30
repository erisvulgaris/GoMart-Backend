<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeScreenModel extends Model
{
    protected $table      = 'home_screens';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'slug', 'is_default', 'status', 'sort_order',
        'header_type', 'gradient_start', 'gradient_end', 'header_gif', 'overlay_text_color',
        'tab_icon', 'tab_active_color', 'tab_inactive_color',
    ];
    protected $returnType = 'array';

    public function getAllHomeScreens()
    {
        return $this->orderBy('sort_order', 'ASC')->findAll();
    }

    public function getActiveHomeScreens()
    {
        // Always include the default screen even if its status is inactive,
        // so the app always has content to show on first load.
        return $this->groupStart()
                        ->where('status', 1)
                        ->orWhere('is_default', 1)
                    ->groupEnd()
                    ->orderBy('is_default', 'DESC') // default screen first
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function getDefaultScreen()
    {
        return $this->where('is_default', 1)->first();
    }

    public function insertHomeScreen($data)
    {
        return $this->insert($data);
    }

    public function deleteHomeScreen($id)
    {
        return $this->delete($id);
    }
}
