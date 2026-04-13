<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('admin/users/index', [
            'title' => 'User Management',
            'users' => $this->userModel->findAll()
        ]);
    }

    public function datatable()
	{
	    $request = service('request');
	    $db      = \Config\Database::connect();
	    $builder = $db->table('users');

	    $columns = ['id', 'name', 'email', 'role'];

	    $start  = $request->getPost('start');
	    $length = $request->getPost('length');
	    $search = $request->getPost('search')['value'];

	    // SEARCH
	    if ($search) {
	        $builder->groupStart()
	            ->like('name', $search)
	            ->orLike('email', $search)
	            ->orLike('role', $search)
	        ->groupEnd();
	    }

	    // ORDER
	    if ($request->getPost('order')) {
	        $colIndex = $request->getPost('order')[0]['column'];
	        $dir      = $request->getPost('order')[0]['dir'];
	        $builder->orderBy($columns[$colIndex], $dir);
	    }

	    $totalFiltered = $builder->countAllResults(false);

	    // LIMIT
	    $data = $builder
	        ->limit($length, $start)
	        ->get()
	        ->getResultArray();

	    $result = [];
	    $no = $start + 1;

	    foreach ($data as $row) {
		    // Tombol delete hanya muncul jika BUKAN user yang sedang login
		    $deleteBtn = '';

		    if ($row['id'] != session()->get('user_id')) {
		        $deleteBtn = '
		            <button
		                class="btn btn-sm btn-danger btn-delete"
		                data-id="'.$row['id'].'">
		                Delete
		            </button>
		        ';
		    }

		    $result[] = [
		        $no++,
		        esc($row['name']),
		        esc($row['email']),
		        '<span class="badge badge-info">'.esc($row['role']).'</span>',
		        '
		        <a href="'.base_url('admin/users/edit/'.$row['id']).'"
		           class="btn btn-sm btn-warning">
		           Edit
		        </a>
		        '.$deleteBtn.'
		        '
		    ];
		}


	    return $this->response->setJSON([
	        'draw'            => intval($request->getPost('draw')),
	        'recordsTotal'    => $db->table('users')->countAll(),
	        'recordsFiltered' => $totalFiltered,
	        'data'            => $result
	    ]);
	}


    public function create()
    {
        return view('admin/users/create', [
            'title' => 'Tambah User'
        ]);
    }

    public function store()
    {
        $this->userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        return redirect()->to('/admin/users')
            ->with('success', 'User added successfully');
    }

    public function edit($id)
    {
        return view('admin/users/edit', [
            'title' => 'Edit User',
            'user'  => $this->userModel->find($id)
        ]);
    }

    public function update($id)
    {
        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            );
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/users')
            ->with('success', 'User updated successfully');
    }

    public function deleteAjax()
	{
	    if (!$this->request->isAJAX()) {
	        return $this->response->setStatusCode(403);
	    }

	    $id = $this->request->getPost('id');
	    $currentUserId = session()->get('user_id');

	    if (!$id) {
	        return $this->response->setJSON([
	            'status' => false,
	            'message' => 'Invalid ID'
	        ]);
	    }

	    // CEGAH HAPUS DIRI SENDIRI
	    if ($id == $currentUserId) {
	        return $this->response->setJSON([
	            'status' => false,
	            'message' => 'You can not delete your own account'
	        ]);
	    }

	    $this->userModel->delete($id);

	    return $this->response->setJSON([
	        'status' => true,
	        'message' => 'User has been successfully deleted'
	    ]);
	}



    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('/admin/users')
            ->with('success', 'User has been successfully deleted');
    }


}