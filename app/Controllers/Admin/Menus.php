<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\MenuCategoryModel;
use App\Models\CategoryModel;

class Menus extends BaseController
{
    protected $menu;
    protected $category;

    public function __construct()
    {
        $this->menu     = new MenuModel();
        $this->category = new MenuCategoryModel();
    }

    // ===============================
    // INDEX PAGE
    // ===============================
    public function index()
    {
        $categoryId = $this->request->getGet('category');

        $menuModel     = new MenuModel();
        $categoryModel = new CategoryModel();

        $menus = $menuModel->getActive($categoryId);

        return view('admin/menus/index', [
            'menus'      => $menus,
            'categories' => $categoryModel->getActive(),
            'activeCat'  => $categoryId
        ]);
    }

    // ===============================
    // DATATABLE SERVER SIDE
    // ===============================
    public function datatable()
    {
        $request = service('request');

        $draw   = intval($request->getPost('draw'));
        $start  = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $search = $request->getPost('search')['value'];

        // BASE QUERY
        $builder = $this->menu
            ->select('menus.id, menus.name, menus.price, menus.image, menus.is_roomservice, menus.is_active, menu_categories.name AS category')
            ->join('menu_categories', 'menu_categories.id = menus.category_id', 'left')
            ->where('menus.deleted_at', null);


        // TOTAL DATA
        $recordsTotal = $builder->countAllResults(false);

        // SEARCH
        if (!empty($search)) {
            $builder->groupStart()
                ->like('menus.name', $search)
                ->orLike('menu_categories.name', $search)
                ->groupEnd();
        }

        // FILTERED DATA
        $recordsFiltered = $builder->countAllResults(false);

        // DATA
        $data = $builder
            ->orderBy('menus.id', 'DESC')
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        $result = [];
        foreach ($data as $row) {
            $image = $row['image']
                ? '<img src="'.base_url('uploads/menus/'.$row['image']).'" 
                        width="60" class="img-thumbnail">'
                : '<span class="text-muted">No Image</span>';

            $result[] = [
                esc($row['name']),
                esc($row['category']),
                'Rp ' . number_format($row['price'], 0, ',', '.'),
                $image,
                $row['is_roomservice']
                    ? '<span class="badge badge-success">Yes</span>'
                    : '<span class="badge badge-secondary">No</span>',
                $row['is_active']
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>',
                '
                <button class="btn btn-sm btn-warning btn-edit"
                    onclick="editMenu('.$row['id'].')">
                    Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteMenu('.$row['id'].')">Delete</button>
                '
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $result
        ]);
    }

    public function show($id)
    {
        return $this->response->setJSON(
            $this->menu->find($id)
        );
    }

    // ===============================
    // CREATE FORM
    // ===============================
    public function create()
    {
        return view('admin/menus/create', [
            'categories' => $this->category->where('is_active', 1)->findAll()
        ]);
    }

    public function store()
    {
        $data = [
            'category_id'       => $this->request->getPost('category_id'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'price'             => $this->request->getPost('price'),
            'is_roomservice'    => $this->request->getPost('is_roomservice'),
            'is_active'         => $this->request->getPost('is_active'),
            'created_by'        => session()->get('user_id')
        ];

        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/menus', $newName);
            $data['image'] = $newName;
        }

        $this->menu->insert($data);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data added successfully'
        ]);
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update($id)
    {
        $old = $this->menu->find($id);
        if (!$old) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Menu not found'
            ]);
        }

        $isActive = $this->request->getPost('is_active');

        $data = [
            'category_id'       => $this->request->getPost('category_id'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'price'             => $this->request->getPost('price'),
            'is_roomservice'    => $this->request->getPost('is_roomservice'),
            'is_active'         => $isActive,
            'updated_by'        => session()->get('user_id'),
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        /* ===============================
         * IMAGE HANDLING
         * =============================== */
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/menus', $newName);
            $data['image'] = $newName;

            // Hapus image lama
            if (!empty($old['image']) && file_exists(FCPATH . 'uploads/menus/' . $old['image'])) {
                unlink(FCPATH . 'uploads/menus/' . $old['image']);
            }
        }

        /* ===============================
         * UPDATE MENU
         * =============================== */
        $this->menu->update($id, $data);

        /* ===============================
         * CASCADE MENU VARIANTS
         * =============================== */
        $variantModel = new \App\Models\MenuVariantModel();

        // JIKA MENU DI-INACTIVE → SOFT DELETE VARIANTS
        if ($old['is_active'] == 1 && $isActive == 0) {
            $variantModel->builder()
                ->where('menu_id', $id)
                ->update([
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'deleted_by' => session()->get('user_id')
                ]);
        }

        // JIKA MENU DIAKTIFKAN KEMBALI → RESTORE VARIANTS
        if ($old['is_active'] == 0 && $isActive == 1) {
            $variantModel->builder()
                ->where('menu_id', $id)
                ->where('deleted_at IS NOT NULL', null, false)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data has been successfully updated'
        ]);
    }

    // ===============================
    // SOFT DELETE
    // ===============================
    public function delete($id)
    {
        $menu = $this->menu->find($id);

        if (!$menu) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data not found'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {

            /* ===============================
             * SOFT DELETE MENU
             * =============================== */
            $this->menu->builder()
                ->where('id', $id)
                ->update([
                    'is_active'  => 0,
                    'deleted_by' => session()->get('user_id'),
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            /* ===============================
             * SOFT DELETE MENU VARIANTS
             * =============================== */
            $variantModel = new \App\Models\MenuVariantModel();

            $variantModel->builder()
                ->where('menu_id', $id)
                ->where('deleted_at IS NULL', null, false)
                ->update([
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'deleted_by' => session()->get('user_id')
                ]);

            $db->transCommit();

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Menu and its variants deleted successfully'
            ]);

        } catch (\Throwable $e) {

            $db->transRollback();

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Failed to delete data'
            ]);
        }
    }

    /* ===============================
     * VARIANTS
     * =============================== */
    public function variants($menuId)
    {
        $variantModel = new \App\Models\VariantModel();

        return $this->response->setJSON(
            $variantModel->where('menu_id', $menuId)->findAll()
        );
    }

}
