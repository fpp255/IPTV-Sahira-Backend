<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MenuVariantModel;
use App\Models\MenuModel;

class MenuVariantApi extends BaseController
{
    protected $variant;
    protected $menu;

    public function __construct()
    {
        $this->variant = new MenuVariantModel();
        $this->menu    = new MenuModel();
    }

    /* =====================================================
     * GET /menus/{menu_id}/variants
     * HANYA jika menu_id ada di table menu
     * ===================================================== */
    public function byMenu($menuId)
    {
        if (!$menuId) {
            return $this->respondSuccess([]); // ⬅️ jangan error
        }

        // ✅ CEK: menu_id HARUS ADA DI TABLE menus
        $menuExists = (new MenuModel())
            ->where('id', $menuId)
            ->where('deleted_at', null)
            ->countAllResults();

        // ❌ JIKA BUKAN menu (HK atau ID random)
        if ($menuExists === 0) {
            // ⬅️ INI KUNCI UTAMANYA
            return $this->respondSuccess([]);
        }

        // ✅ AMBIL VARIANT HANYA DARI menu_variants
        $variants = $this->variant
            ->select('id, menu_id, name, price')
            ->where('menu_id', $menuId)
            ->where('deleted_at', null)
            ->orderBy('id', 'ASC')
            ->findAll();

        return $this->respondSuccess($variants);
    }

    /* =====================================================
     * HELPER: success response
     * ===================================================== */
    private function respondSuccess($data)
    {
        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    /* =====================================================
     * HELPER: error response
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
}
