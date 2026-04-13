<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\MenuVariantModel;
use App\Models\HkMenuModel;

class MenuApi extends BaseController
{
    protected $menu;
    protected $variant;
    protected $hk_menu;

    public function __construct()
    {
        $this->menu    = new MenuModel();
        $this->variant = new MenuVariantModel();
        $this->hk_menu = new HkMenuModel();
    }

    /* =====================================================
     * GET /api/menus
     * Semua menu aktif (untuk home IPTV / mobile)
     * ===================================================== */
    public function index()
    {
        $menus = $this->menu
            ->select('id, category_id, name, description, price, image')
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->respondSuccess(
            $this->mapMenus($menus)
        );
    }

    /* =====================================================
     * GET /api/menus/category/{id}
     * Menu berdasarkan kategori
     * ===================================================== */
    public function byCategory($categoryId)
    {
        $menus = $this->menu
            ->select('id, category_id, name, description, price, image')
            ->where([
                'category_id' => $categoryId,
                'is_active'   => 1,
                'deleted_at'  => null
            ])
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->respondSuccess(
            $this->mapMenus($menus)
        );
    }

    /* =====================================================
     * GET /api/menus/{id}
     * Detail menu + variants
     * ===================================================== */
    public function show($id)
    {
        $menu = $this->menu
            ->where('id', $id)
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->first();

        if (!$menu) {
            return $this->respondError('Menu not found', 404);
        }

        $variants = $this->variant
            ->select('id, name, price')
            ->where('menu_id', $id)
            ->where('deleted_at', null)
            ->findAll();

        return $this->respondSuccess([
            'id'          => $menu['id'],
            'category_id' => $menu['category_id'],
            'name'        => $menu['name'],
            'description' => $menu['description'],
            'price'       => (float)$menu['price'],
            'image'       => $this->imageUrl($menu['image']),
            'variants'    => $variants
        ]);
    }

    /* =====================================================
     * HELPER: format menu list
     * ===================================================== */
    private function mapMenus(array $menus): array
    {
        return array_map(function ($m) {
            return [
                'id'          => $m['id'],
                'category_id' => $m['category_id'],
                'name'        => $m['name'],
                'description' => $m['description'],
                'price'       => (float)$m['price'],
                'image'       => $this->imageUrl($m['image'])
            ];
        }, $menus);
    }

    /* =====================================================
     * HELPER: image full URL
     * ===================================================== */
    private function imageUrl($image): ?string
    {
        if (!$image) {
            return null;
        }

        return base_url('uploads/menus/' . $image);
    }

    /* =====================================================
     * HELPER: standard success response
     * ===================================================== */
    private function respondSuccess($data)
    {
        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    /* =====================================================
     * HELPER: standard error response
     * ===================================================== */
    private function respondError($message, $code = 400)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON([
                'status'  => false,
                'message' => $message
            ]);
    }

    /* =====================================================
     * GET /api/menus/roomservice
     * Semua menu khusus room service
     * ===================================================== */
    public function roomService()
    {
        $menus = $this->menu
            ->select('id, category_id, name, description, price, image')
            ->where([
                'is_roomservice' => 1,
                'is_active'      => 1,
                'deleted_at'     => null
            ])
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->respondSuccess(
            $this->mapMenus($menus)
        );
    }

    public function roomServiceByCategory($categoryId)
    {
        $menus = $this->menu
            ->select('id, category_id, name, description, price, image')
            ->where([
                'category_id'    => $categoryId,
                'is_roomservice' => 1,
                'is_active'      => 1,
                'deleted_at'     => null
            ])
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->respondSuccess(
            $this->mapMenus($menus)
        );
    }

    public function houseKeepingByCategory($categoryId)
    {
        $hk_menus = $this->hk_menu
            ->select('id, category_id, name, description, price, image')
            ->where([
                'category_id'    => $categoryId,
                'is_roomservice' => 0,
                'is_active'      => 1,
                'deleted_at'     => null
            ])
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->respondSuccess(
            $this->mapMenus($hk_menus)
        );
    }

}
