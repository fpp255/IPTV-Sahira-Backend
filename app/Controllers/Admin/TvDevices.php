<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TvDeviceModel;

class TvDevices extends BaseController
{
    protected $tv;

    public function __construct()
    {
        $this->tv = new TvDeviceModel();
    }

    public function index()
    {
        return view('admin/tv_devices/index', [
            'title' => 'TV Management'
        ]);
    }

    // DATATABLE
    public function datatable()
    {
        $request = service('request');
        $db      = \Config\Database::connect();
        $builder = $db->table('tv_devices');

        // FILTER SOFT DELETE
        $builder->where('deleted_at', null);

        $columns = [
            0 => 'device_id',
            1 => 'room_no',
            2 => 'id'
        ];

        if ($search = $request->getPost('search')['value']) {
            $builder->groupStart()
                ->like('device_id', $search)
                ->orLike('room_no', $search)
                ->groupEnd();
        }

        // TOTAL FILTERED
        $totalFiltered = $builder->countAllResults(false);

        // ORDER (DEFAULT DESC)
        if ($request->getPost('order')) {
            $colIndex = $request->getPost('order')[0]['column'];
            $dir      = $request->getPost('order')[0]['dir'];

            $builder->orderBy($columns[$colIndex], $dir);
        } else {
            // default terbaru
            $builder->orderBy('id', 'DESC');
        }

        // LIMIT
        $builder->limit(
            $request->getPost('length'),
            $request->getPost('start')
        );

        $query = $builder->get()->getResultArray();

        $data = [];
        $no   = $request->getPost('start') + 1;
        foreach ($query as $row) {
            $data[] = [
                $no++,
                esc($row['device_id']),
                esc($row['room_no']),
                '
                <button class="btn btn-sm btn-warning btn-edit"
                    data-id="'.$row['id'].'"
                    data-device="'.esc($row['device_id']).'"
                    data-room="'.esc($row['room_no']).'">Edit
                </button>
                <button class="btn btn-sm btn-danger btn-delete"
                    data-id="'.$row['id'].'">Delete
                </button>
                '
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => $this->tv->where('deleted_at', null)->countAllResults(),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $userId = session()->get('user_id');

        // CEK DUPLIKASI DEVICE / ROOM
        $duplicate = $this->tv
            ->groupStart()
                ->where('device_id', $this->request->getPost('device_id'))
                ->orWhere('room_no', $this->request->getPost('room_no'))
            ->groupEnd()
            ->where('id !=', $id ?? 0)
            ->where('deleted_at', null)
            ->first();

        if ($duplicate) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Device ID or Room already registered'
            ]);
        }

        $data = [
            'device_id' => $this->request->getPost('device_id'),
            'room_no'   => $this->request->getPost('room_no'),
        ];

        if ($id) {
            $data['updated_by'] = $userId;
            $this->tv->update($id, $data);
        } else {
            $data['created_by'] = $userId;
            $this->tv->insert($data);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => $id ? 'Updated successfully' : 'Created successfully'
        ]);
    }


    // UPDATE
    public function update()
    {
        $id = $this->request->getPost('id');

        $this->tv->update($id, [
            'device_id'     => $this->request->getPost('device_id'),
            'room_no'       => $this->request->getPost('room_no'),
            'updated_by'    => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'TV device updated'
        ]);
    }

    public function delete($id)
    {
        $this->tv->update($id, [
            'deleted_by' => session()->get('user_id')
        ]);

        $this->tv->delete($id);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Deleted successfully'
        ]);
    }

}
