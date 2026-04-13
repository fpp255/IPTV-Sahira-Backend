<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HkModel;
use App\Models\HkCategoryModel;

class Hkitems extends BaseController
{
    protected $hk;
    protected $category;

    public function __construct()
    {
        $this->hk       = new HkModel();
        $this->category = new HkCategoryModel();
    }

    public function index()
    {
        return view('admin/hk_items/index', [
            'title' => 'Housekeeping Management',
            'categories' => $this->category
                ->where('deleted_at', null)
                ->where('is_active', 1)
                ->findAll()
        ]);
    }

    /* =========================
     * DATATABLE
     * ========================= */
    public function datatable()
    {
        $request = service('request');
        $db      = \Config\Database::connect();
        $builder = $db->table('hk');
        $builder->where('hk.deleted_at', null);
        $search = $request->getPost('search')['value'];

        $builder->select('hk.id, hk.name, hk.is_active, hk.category_id, hk_menu_categories.name AS category');
        $builder->join('hk_menu_categories', 'hk_menu_categories.id = hk.category_id');
        $builder->where('hk.deleted_at', null);
        $builder->orderBy('hk.id', 'DESC');

        $columns = ['id','name', 'category_id', 'is_active','id'];

        if (!empty($search)) {
            $builder->groupStart()
                ->like('hk.name', $search)
                ->orLike('hk_categories.name', $search)
                ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(false);

        if ($request->getPost('order')) {
            $builder->orderBy(
                $columns[$request->getPost('order')[0]['column']],
                $request->getPost('order')[0]['dir']
            );
        } else {
            $builder->orderBy('id', 'DESC');
        }

        $builder->limit(
            $request->getPost('length'),
            $request->getPost('start')
        );

        $data = [];
        $no   = $request->getPost('start') + 1;
        foreach ($builder->get()->getResultArray() as $row) {
            $data[] = [
                $no++,
                esc($row['name']),
                esc($row['category']),
                $row['is_active']
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>',
                '
                <button class="btn btn-sm btn-warning btn-edit"
                    data-id="'.$row['id'].'"
                    data-name="'.esc($row['name']).'"
                    data-category-id="'.$row['category_id'].'"
                    data-active="'.$row['is_active'].'">Edit
                </button>
                <button class="btn btn-sm btn-danger btn-delete"
                    data-id="'.$row['id'].'">
                    Delete
                </button>
                '
            ];
        }

        return $this->response->setJSON([
            'draw'            => intval($request->getPost('draw')),
            'recordsTotal'    => $this->hk->where('deleted_at', null)->countAllResults(),
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store()
    {
        $this->hk->insert([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'is_active'   => $this->request->getPost('is_active'),
            'created_by'  => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message'=> 'Data added successfully'
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id)
    {
        $this->hk->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'is_active'   => $this->request->getPost('is_active'),
            'updated_by'  => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data updated successfully'
        ]);
    }

    /* =========================
     * DELETE (SOFT)
     * ========================= */
    public function delete($id)
    {
        
        $this->hk->update($id, [
            'is_active'  => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data successfully deleted'
        ]);
    }
}