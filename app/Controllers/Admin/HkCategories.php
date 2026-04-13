<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HkCategoryModel;
use App\Models\HkModel;

class HkCategories extends BaseController
{
    protected $category;
    protected $hk;

    public function __construct()
    {
        $this->category = new HkCategoryModel();
        $this->hk       = new HkModel();
    }

    public function index()
    {
        return view('admin/hk_categories/index');
    }

    /* ======================
     * DATATABLE SERVER SIDE
     * ====================== */
    public function datatable()
    {
        $request = service('request');
        $db      = db_connect();

        $builder = $db->table('hk_menu_categories')
            ->where('deleted_at', null)
            ->orderBy('id', 'DESC');

        $totalData = $builder->countAllResults(false);

        // Search
        if ($search = $request->getPost('search')['value']) {
            $builder->like('name', $search);
        }

        $filteredData = $builder->countAllResults(false);

        // Order
        $columns = ['id','name','sort_order','is_active'];
        $orderCol = $columns[$request->getPost('order')[0]['column']];
        $orderDir = $request->getPost('order')[0]['dir'];
        $builder->orderBy($orderCol, $orderDir);

        // Limit
        $builder->limit(
            $request->getPost('length'),
            $request->getPost('start')
        );

        $data = [];
        $no   = $request->getPost('start') + 1;
        foreach ($builder->get()->getResult() as $row) {
            $data[] = [
                $no++,
                esc($row->name),
                $row->sort_order,
                $row->is_active 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-secondary">Inactive</span>',
                '
                <button class="btn btn-sm btn-warning edit"
                    data-id="'.$row->id.'"
                    data-name="'.$row->name.'"
                    data-sort="'.$row->sort_order.'"
                    data-active="'.$row->is_active.'">
                    Edit
                </button>
                <button class="btn btn-sm btn-danger delete"
                    data-id="'.$row->id.'">
                    Delete
                </button>
                '
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $filteredData,
            "data"            => $data
        ]);
    }

    /* ==========
     * STORE
     * ========== */
    public function store()
    {
        // ambil sort_order terakhir
        $lastSort = $this->category
            ->selectMax('sort_order')
            ->where('deleted_at', null)
            ->first();

        $newSortOrder = ($lastSort && $lastSort['sort_order'])
            ? $lastSort['sort_order'] + 1
            : 1;

        $this->category->insert([
            'name'       => $this->request->getPost('name'),
            'sort_order' => $newSortOrder, // 🔥 otomatis
            'is_active'  => $this->request->getPost('is_active'),
            'created_by' => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data added successfully'
        ]);
    }

    /* ==========
     * UPDATE
     * ========== */
    public function update($id)
    {
        $isActive = $this->request->getPost('is_active');

        // update category
        $this->category->update($id, [
            'name'       => $this->request->getPost('name'),
            'sort_order' => $this->request->getPost('sort_order'),
            'is_active'  => $isActive,
            'updated_by' => session()->get('user_id')
        ]);

        /*
         |=================================================
         | JIKA CATEGORY DI-INACTIVE-KAN
         | MAKA SEMUA HK TERKAIT JUGA DI-INACTIVE-KAN
         |=================================================
         */
        if ($isActive == 0) {
            $this->hk
                ->where('category_id', $id)
                ->set([
                    'is_active'  => 0,
                    'updated_at'=> date('Y-m-d H:i:s'),
                    'updated_by'=> session()->get('user_id')
                ])
                ->update();
        } else {
            $this->hk
                ->where('category_id', $id)
                ->set([
                    'is_active'  => 1,
                    'updated_at'=> date('Y-m-d H:i:s'),
                    'updated_by'=> session()->get('user_id')
                ])
                ->update();
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data updated successfully'
        ]);
    }

    /* ===========================
     * DELETE + CASCADE SOFT DELETE
     * =========================== */
    public function delete($id)
    {
        // Soft delete category
        $this->category->update($id, [
            'deleted_by' => session()->get('user_id')
        ]);
        $this->category->delete($id);

        // Soft delete semua HK yang terkait
        $this->hk
            ->where('category_id', $id)
            ->set([
                'deleted_at'=> date('Y-m-d H:i:s'),
                'deleted_by' => session()->get('user_id')
            ])
            ->update();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Category and related HK deleted successfully'
        ]);
    }

    /* ======================
     * REORDER SORT ORDER
     * ====================== */
    public function reorder()
    {
        $orders = $this->request->getPost('order'); 
        // contoh: [3, 1, 5, 2]

        if (!$orders || !is_array($orders)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid data'
            ]);
        }

        $db = db_connect();
        $db->transStart();

        $sort = 1;
        foreach ($orders as $id) {
            $this->category->update($id, [
                'sort_order' => $sort,
                'updated_by' => session()->get('user_id')
            ]);
            $sort++;
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Category order updated'
        ]);
    }

    public function sort()
    {
        $orders = $this->request->getPost('order');

        if (!$orders) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid data'
            ]);
        }

        foreach ($orders as $item) {
            $this->category->update($item['id'], [
                'sort_order' => $item['sort_order']
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Category order updated'
        ]);
    }

    public function sortView()
    {
        $categories = $this->category
            ->where('deleted_at', null)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('admin/hk_categories/sort', [
            'categories' => $categories
        ]);
    }
}
