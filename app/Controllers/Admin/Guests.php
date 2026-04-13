<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuestModel;
use CodeIgniter\Database\Config;

class Guests extends BaseController
{
    protected $guestModel;

    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }

    /* =========================
     *  PAGE
     * ========================= */
    public function index()
    {
        return view('admin/guests/index');
    }

    /* =========================
     *  DATATABLE SERVER SIDE
     * ========================= */
    public function datatable()
    {
        $request = service('request');
        $db      = \Config\Database::connect();
        $builder = $db->table('guests');

        // Soft delete filter
        $builder->where('deleted_at', null);

        $columns = [
            0 => 'guest_name',
            1 => 'device_id',
            2 => 'room_no',
            3 => 'status',
            4 => 'checkin_date',
            5 => 'checkout_date',
            6 => null // action (tidak bisa sort)
        ];

        // Search
        if ($search = $request->getPost('search')['value']) {
            $builder->groupStart()
                ->like('guest_name', $search)
                ->orLike('status', $search)
                ->orLike('device_id', $search)
                ->orLike('room_no', $search)
                ->groupEnd();
        }

        // Total filtered
        $totalFiltered = $builder->countAllResults(false);

        // ORDERING
        if ($request->getPost('order')) {
            $colIndex = $request->getPost('order')[0]['column'];
            $orderDir = $request->getPost('order')[0]['dir'];

            if (!empty($columns[$colIndex])) {
                $builder->orderBy($columns[$colIndex], $orderDir);
            }
        } else {
            $builder->orderBy('checkin_date', 'DESC');
        }

        // Limit
        $builder->limit(
            $request->getPost('length'),
            $request->getPost('start')
        );

        $query = $builder->get()->getResultArray();

        $data = [];
        $no   = $request->getPost('start') + 1;

        foreach ($query as $row) {
            $checkin  = $row['checkin_date']
                ? date('d-m-Y', strtotime($row['checkin_date']))
                : '-';
            $checkout = $row['checkout_date']
                ? date('d-m-Y', strtotime($row['checkout_date']))
                : '-';
            $data[] = [
                esc($row['guest_name']),
                esc($row['device_id']),
                esc($row['room_no']),
                '<span class="badge badge-info">'.$row['status'].'</span>',
                $checkin,
                $checkout,
                '
                <button class="btn btn-sm btn-warning btn-edit"
                        data-id="'.$row['id'].'">
                    Edit
                </button>
                <button class="btn btn-sm btn-danger btn-delete"
                        data-id="'.$row['id'].'">
                    Delete
                </button>
                '
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            // "recordsTotal"    => $this->guestModel->countAll(),
            "recordsTotal"    => $this->guestModel->where('deleted_at', null)->countAllResults(),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        ]);
    }

    public function roomDevices()
    {
        $db = \Config\Database::connect();

        $rooms = $db->table('tv_devices td')
            ->select('
                td.room_no,
                td.device_id,
                IF(g.id IS NULL, 0, 1) AS is_locked
            ')
            ->join(
                'guests g',
                'g.room_no = td.room_no
                 AND g.status IN ("ACTIVE")
                 AND g.deleted_at IS NULL',
                'left'
            )
            ->where('td.deleted_at', null)
            ->orderBy('td.room_no', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($rooms);
    }


    /* =========================
     *  STORE
     * ========================= */
    public function store()
    {
        $room = $this->request->getPost('room_no');

        $exists = $this->guestModel
            ->where('room_no', $room)
            ->whereIn('status', ['ACTIVE'])
            ->where('deleted_at', null)
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Room already occupied'
            ]);
        }

        $this->guestModel->insert([
            'guest_name'    => $this->request->getPost('guest_name'),
            'room_no'       => $this->request->getPost('room_no'),
            'device_id'     => $this->request->getPost('device_id'),
            'status'        => $this->request->getPost('status'),
            'checkin_date'  => $this->request->getPost('checkin_date'),
            'checkout_date' => $this->request->getPost('checkout_date'),
            'created_by'    => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Guest data added successfully'
        ]);
    }

    /* =========================
     *  GET SINGLE (EDIT)
     * ========================= */
    public function edit($id)
    {
        $guest = $this->guestModel->find($id);

        if (!$guest) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $guest
        ]);
    }

    /* =========================
     *  UPDATE
     * ========================= */
    public function update()
    {
        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $room   = $this->request->getPost('room_no');

        $exists = $this->guestModel
            ->where('room_no', $room)
            ->whereIn('status', ['ACTIVE'])
            ->where('deleted_at', null)
            ->where('id !=', $id)
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Room already occupied'
            ]);
        }

        $data = [
            'guest_name'    => $this->request->getPost('guest_name'),
            'room_no'       => $this->request->getPost('room_no'),
            'device_id'     => $this->request->getPost('device_id'),
            'status'        => $status,
            'checkin_date'  => $this->request->getPost('checkin_date'),
            'checkout_date' => $this->request->getPost('checkout_date')
        ];

        if ($status === 'CHECKOUT') {
            $data['device_id'] = null;
        } else {
            $data['device_id'] = $this->request->getPost('device_id');
        }

        $this->guestModel->update($id, $data);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data updated successfully'
        ]);
    }

    /* =========================
     *  Quict checkout
     * ========================= */
    public function checkout()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid guest ID'
            ]);
        }

        $guest = $this->guestModel
            ->where('id', $id)
            ->whereIn('status', ['ACTIVE'])
            ->where('deleted_at', null)
            ->first();

        if (!$guest) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Guest not found or already checked out'
            ]);
        }

        // UPDATE CHECKOUT
        $this->guestModel->update($id, [
            'device_id'     => null,
            'status'        => 'CHECKOUT',
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Guest successfully checked out'
        ]);
    }

    /* =========================
     *  SOFT DELETE
     * ========================= */
    public function deleteAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $id = $this->request->getPost('id');

        $this->guestModel->update($id, [
            'device_id'  => null,
            'status'     => 'CHECKOUT'
        ]);

        $this->guestModel->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Guest data has been successfully deleted'
        ]);
    }

    /* =========================
     *  RESTORE
     * ========================= */
    public function restore()
    {
        $id = $this->request->getPost('id');

        $this->guestModel
            ->withDeleted()
            ->update($id, ['deleted_at' => null]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data tamu berhasil direstore'
        ]);
    }
}
