<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuCategoryModel;

class MenuCategories extends BaseController
{
    protected $category;

    public function __construct()
    {
        $this->category = new MenuCategoryModel();
    }

    // LIST PAGE
    public function index()
    {
        return view('admin/menu_categories/index');
    }

    // DATATABLE SERVER SIDE
    public function datatable()
    {
        $request = service('request');

        $search = $request->getPost('search')['value'] ?? '';

        $builder = $this->category->builder();
        $builder->where('deleted_at', null);
        $builder->orderBy('id', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);

        $builder->limit(
            $request->getPost('length'),
            $request->getPost('start')
        );

        $data = $builder->get()->getResultArray();

        $rows = [];
        $no = $request->getPost('start') + 1;

        foreach ($data as $row) {
            $rows[] = [
                $no++,
                esc($row['name']),
                $row['sort_order'],
                $row['is_active']
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>',
                '
                <button class="btn btn-sm btn-warning" onclick="editCategory('.$row['id'].')">
                    Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteCategory('.$row['id'].')">
                    Delete
                </button>'
            ];
        }

        return $this->response->setJSON([
            'draw'            => intval($request->getPost('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows
        ]);
    }

    public function lastSortOrder()
	{
	    $last = $this->category
	        ->select('sort_order')
	        ->orderBy('sort_order', 'DESC')
	        ->first();

	    return $this->response->setJSON([
	        'sort_order' => $last ? ($last['sort_order'] + 1) : 1
	    ]);
	}

    // STORE
    public function store()
    {
        if (!$this->request->getPost('sort_order')) {
		    $last = $this->category->orderBy('sort_order', 'DESC')->first();
		    $data['sort_order'] = $last ? $last['sort_order'] + 1 : 1;
		}

        $this->category->insert([
            'name'       => $this->request->getPost('name'),
            'sort_order' => $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active'),
            'created_by' => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data added successfully'
        ]);
    }

    // SHOW (EDIT)
    public function show($id)
    {
        return $this->response->setJSON(
            $this->category->find($id)
        );
    }

    // UPDATE
    public function update($id)
    {
        $isActive = $this->request->getPost('is_active');

        // Update category
        $this->category->update($id, [
            'name'       => $this->request->getPost('name'),
            'sort_order' => $this->request->getPost('edit_sort_order'),
            'is_active'  => $isActive,
            'updated_by' => session()->get('user_id')
        ]);

        // RUBAH STATUS DI MENU
        if ($isActive == 0) {
            $menuModel = new \App\Models\MenuModel();
            $menuModel->where('category_id', $id)
                      ->set([
                          'is_active'  => 0,
                          'updated_by' => session()->get('user_id')
                      ])
                      ->update();
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data updated successfully'
        ]);
    }

    // DELETE (SOFT)
    public function delete($id)
    {
        $userId = session()->get('user_id');

        // Soft delete semua menu dengan kategori yg berelasi
        $menuModel = new \App\Models\MenuModel();

        $menuModel->where('category_id', $id)
                  ->set([
                      'is_active' => 0,
                      'deleted_by' => $userId
                  ])
                  ->update();

        $menuModel->where('category_id', $id)
                  ->delete(); // soft delete menus

        // Soft delete kategori
        $this->category->update($id, [
            'is_active' => 0,
            'deleted_by' => $userId
        ]);

        $this->category->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Category and menus has been successfully deleted.'
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

        return view('admin/menu_categories/sort', [
            'categories' => $categories
        ]);
    }

}
