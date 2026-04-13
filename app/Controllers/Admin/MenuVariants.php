<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuVariantModel;
use App\Models\MenuModel;

class MenuVariants extends BaseController
{
    protected $variant;
    protected $menu;

    public function __construct()
    {
        $this->variant = new MenuVariantModel();
        $this->menu    = new MenuModel();
    }

    public function index()
    {
        return view('admin/menu_variants/index', [
            'menus' => $this->menu
                ->where('deleted_at', null)
                ->where('is_active', 1)
                ->findAll()
        ]);
    }

    // ================= DATATABLE =================
    public function datatable()
    {
        $request = service('request');

        $search = $request->getPost('search')['value'] ?? '';

        $builder = $this->variant->builder();
        $builder->select('menu_variants.*, menus.name AS menu_name');
        $builder->join('menus', 'menus.id = menu_variants.menu_id');
        $builder->where('menu_variants.deleted_at', null);
        $builder->orderBy('menu_variants.id', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('menu_variants.name', $search)
                ->orLike('menus.name', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);

        $builder->limit(
            $request->getPost('length'),
            $request->getPost('start')
        );

        $data = $builder->get()->getResultArray();

        $rows = [];
        $no   = $request->getPost('start') + 1;

        foreach ($data as $row) {
            $rows[] = [
                $no++,
                esc($row['menu_name']),
                esc($row['name']),
                number_format($row['price'], 0, ',', '.'),
                '
                <button class="btn btn-sm btn-warning" onclick="editVariant('.$row['id'].')">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteVariant('.$row['id'].')">Delete</button>
                '
            ];
        }

        return $this->response->setJSON([
            'draw'            => intval($request->getPost('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows
        ]);
    }

    // ================= CREATE =================
    public function store()
    {
        $this->variant->insert([
            'menu_id'    => $this->request->getPost('menu_id'),
            'name'       => $this->request->getPost('name'),
            'price'      => $this->request->getPost('price'),
            'created_by' => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data added successfully'
        ]);
    }

    // ================= SHOW =================
    public function show($id)
    {
        return $this->response->setJSON(
            $this->variant->find($id)
        );
    }

    // ================= UPDATE =================
    public function update($id)
	{
	    if (!$this->variant->find($id)) {
	        return $this->response->setJSON([
	            'status' => false,
	            'message' => 'Data not found'
	        ]);
	    }

	    $this->variant->update($id, [
	        'menu_id'    => $this->request->getPost('edit_menu_id'),
	        'name'       => $this->request->getPost('edit_name'),
	        'price'      => $this->request->getPost('edit_price'),
	        'updated_by' => session()->get('user_id')
	    ]);

	    return $this->response->setJSON([
	        'status'  => true,
	        'message' => 'Data updated successfully'
	    ]);
	}

    // ================= DELETE (SOFT) =================
    public function delete($id)
    {
        $this->variant->update($id, [
            'deleted_by' => session()->get('user_id')
        ]);

        $this->variant->delete($id);

        return $this->response->setJSON([
	        'status'  => true,
	        'message' => 'Data successfully deleted'
	    ]);
    }
}
