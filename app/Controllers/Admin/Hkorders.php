<?php

namespace App\Controllers\Admin;

use App\Models\HkorderModel;
use App\Models\HkorderItemModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Controllers\BaseController;
use App\Models\HkModel;
use App\Models\HkCategoryModel;

class Hkorders extends BaseAdminController
{
    protected $order;
    protected $item;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->order = new HkorderModel();
        $this->item  = new HkorderItemModel();

        // PAKAI DB DARI MODEL (SATU INSTANCE)
        $this->db = $this->order->db;
    }

    /* ===============================
     * PAGE
     * =============================== */
    public function index()
    {
        $menus = $this->menu
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->findAll();

        return view('admin/hkorders/index', compact('menus'));
    }

    /* ===============================
     * DATATABLE
     * =============================== */
    public function datatable()
    {
        $request = service('request');
        $builder = $this->order->builder();

        // =======================
        // GLOBAL SEARCH
        // =======================
        $search = $request->getPost('search')['value'] ?? null;

        if (!empty($search)) {

            // Deteksi format tanggal dd-mm-yyyy
            $isDate = preg_match('/\d{2}-\d{2}-\d{4}/', $search);
            $builder->groupStart()

                // Order No
                ->like('order_no', $search)

                // Room / Table
                ->orLike('room_no', $search)

                // Guest
                ->orLike('guest_name', $search)

                // Status
                ->orLike('status', $search);

            // Jika input mirip tanggal (dd-mm-yyyy)
            if ($isDate) {
                $date = date('Y-m-d', strtotime($search));
                $builder->orLike('DATE(created_at)', $date);
            } else {
                // fallback search biasa
                $builder->orLike('created_at', $search);
            }

            $builder->groupEnd();
        }

        // Mapping index kolom → field database
        $columnMap = [
            1 => 'order_no',
            2 => 'room_no',
            3 => 'guest_name',
            4 => 'status',
            5 => 'created_at'
        ];

        // ORDERING dari DataTables
        $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 5;
        $orderDir         = $request->getPost('order')[0]['dir'] ?? 'desc';

        if (isset($columnMap[$orderColumnIndex])) {
            $builder->orderBy($columnMap[$orderColumnIndex], $orderDir);
        } else {
            $builder->orderBy('created_at', 'DESC');
        }

        // Total data
        $recordsTotal = $builder->countAllResults(false);

        // Paging
        $builder->limit(
            (int) $request->getPost('length'),
            (int) $request->getPost('start')
        );

        $data = $builder->get()->getResultArray();

        $rows = [];
        $no = $request->getPost('start') + 1;

        foreach ($data as $o) {
            $date = !empty($o['created_at'])
                ? date('d-m-Y H:i', strtotime($o['created_at']))
                : '-';

            $status = strtoupper($o['status'] ?? 'PENDING');
            $badgeClass = ($status === 'FINISH') ? 'success' : 'warning';

            $rows[] = [
                $no++,
                esc($o['order_no']),
                esc($o['room_no'] ?: '-'),
                esc($o['guest_name'] ?: '-'),
                '<span class="badge badge-' . $badgeClass . '">' . esc($status) . '</span>',
                $date, // formatted, sorting tetap pakai DB
                '
                <button class="btn btn-sm btn-info" onclick="detailOrder('.$o['id'].')">Detail</button>
                <button class="btn btn-sm btn-warning" onclick="openEditModal('.$o['id'].')">Edit</button>
                '
            ];
        }

        return $this->response->setJSON([
            'draw'            => (int) $request->getPost('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => $rows
        ]);
    }


    /* ===============================
     * CREATE ORDER
     * =============================== */
    public function create()
    {
        $categoryId = $this->request->getGet('category');

        $menuModel     = new MenuModel();
        $categoryModel = new CategoryModel();

        return view('admin/orders/create', [
            'menus'      => $menuModel->getActive($categoryId), // ⬅️ ONLY ACTIVE
            'categories' => $categoryModel->getActive(),
            'activeCat'  => $categoryId
        ]);
    }

    /* ===============================
     * STORE ORDER
     * =============================== */
    public function store()
    {
        $items = $this->request->getPost('items');

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Order items are empty'
            ]);
        }

        // PAKAI DB DARI MODEL (SATU INSTANCE)
        $db = $this->order->db;

        $db->transStart();

        // =========================
        // HITUNG TOTAL
        // =========================
        $total = 0;
        foreach ($items as $i) {
            $total += ((float)$i['price'] * (int)$i['qty']);
        }

        // =========================
        // INSERT ORDER
        // =========================
        $orderId = $this->order->insert([
            'order_no'       => 'ORD' . date('YmdHis'),
            'room_no'        => $this->request->getPost('room_no'),
            'guest_name'     => $this->request->getPost('guest_name'),
            'order_type'     => $this->request->getPost('order_type'),
            'total'          => $total,
            'status'         => 'PENDING',
            'payment_status' => 'UNPAID',
            'created_by'     => session()->get('user_id')
        ], true);

        if (!$orderId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Failed insert order'
            ]);
        }

        // =========================
        // INSERT ITEMS
        // =========================
        foreach ($items as $item) {
            $this->item->insert([
                'order_id'   => $orderId,
                'menu_id'    => $item['menu_id'],
                'menu_name'  => $item['menu_name'],
                'variant_id' => $item['variant_id'] ?? null,
                'variant_name' => $item['variant_name'] ?? null,
                'qty'        => (int)$item['qty'],
                'price'      => (float)$item['price'],
                'notes'      => $item['notes'] ?? null,
                'subtotal'   => (float)$item['price'] * (int)$item['qty']
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Transaction rollback'
            ]);
        }

        return $this->response->setJSON([
            'status'   => true,
            'order_id'=> $orderId,
            'total'   => $total
        ]);
    }

    public function updatePayment()
    {
        $orderId = (int)$this->request->getPost('order_id');
        if($orderId <= 0) {
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'Invalid order_id'
            ]);
        }
        $method  = $this->request->getPost('payment_method');

        if($orderId <= 0 || !$this->order->find($orderId)){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'Order not found'
            ]);
        }

        $this->order->update($orderId,[
            'payment_method'=>$method,
            'payment_status'=>'PAID'
        ]);

        return $this->response->setJSON(['status'=>true]);
    }

    /* ===============================
     * SHOW DETAIL
     * =============================== */
    public function show($id)
    {
        $order = $this->order->find($id);
        $items = $this->item->where('order_id', $id)->findAll();

        return $this->response->setJSON([
            'order' => $order,
            'items' => $items
        ]);
    }

    /* ===============================
     * UPDATE STATUS
     * =============================== */
    public function updateStatus($id)
    {
        $orderStatus   = $this->request->getPost('order_status');

        $data = [
            'status'     => $orderStatus,
            'updated_by' => session()->get('user_id')
        ];

        $this->order->update($id, $data);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data updated successfully'
        ]);
    }

    public function detail($id)
    {
        $order = $this->order->find($id);

        if (!$order) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Order not found'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'id' => $order['id'],
                'status' => $order['status'],
                'payment_status' => $order['payment_status']
            ]
        ]);
    }

}
