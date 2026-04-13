<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MenuCategoryModel;
use App\Models\HkMenuCategoryModel;

class CategoryApi extends BaseController
{
    protected $category;
    protected $hk_category;

    public function __construct()
    {
        $this->category = new MenuCategoryModel();
        $this->hk_category = new HkMenuCategoryModel();
    }

    public function index()
    {
        $categories = $this->category
            ->select('id, name')
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $categories
        ]);
    }

    public function houseKeeping()
    {
        $hk_category = $this->hk_category
            ->select('id, name')
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $hk_category
        ]);
    }
}
