<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\HkOrderModel;
use App\Models\HkOrderItemModel;
use App\Models\MenuModel;
use App\Models\HkMenuModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class OrderApi extends BaseController
{
    protected $order;
    protected $item;
    protected $hk_order;
    protected $hk_item;
    protected $menu;
    protected $hk_menu;
    protected $db;

    public function __construct()
    {
        $this->order = new OrderModel();
        $this->item  = new OrderItemModel();
        $this->hk_order = new HkOrderModel();
        $this->hk_item  = new HkOrderItemModel();
        $this->menu  = new MenuModel();
        $this->hk_menu  = new HkMenuModel();

        // 🔥 SATU INSTANCE DB (TRANSAKSI AMAN)
        $this->db = $this->order->db;
    }

    /* =====================================================
     * CREATE ORDER (ROOM SERVICE / IPTV / ANDROID)
     * POST /api/orders
     * ===================================================== */
    public function store()
    {
        $payload = $this->request->getJSON(true);

        if (!$payload || empty($payload['items'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Order items are empty'
            ])->setStatusCode(400);
        }

        $this->db->transStart();

        try {

            // =========================
            // HITUNG TOTAL
            // =========================
            $total = 0;
            foreach ($payload['items'] as $i) {
                $total += ((float)$i['price'] * (int)$i['qty']);
            }

            $requestType   = strtolower($payload['req_type'] ?? '');
            $paymentMethod = strtoupper($payload['payment_method'] ?? 'CASH');

            $isHousekeeping = $requestType === 'housekeeping';

            // 🔑 ORDER NO DINAMIS
            $orderNo = ($isHousekeeping ? 'HK' : 'ORD') . date('YmdHis');

            // ==================================================
            // 🧹 HOUSEKEEPING ORDER
            // ==================================================
            if ($isHousekeeping) {

                $hkOrderId = $this->hk_order->insert([
                    'order_no'       => $orderNo,
                    'room_no'        => $payload['room_no'] ?? null,
                    'guest_name'     => $payload['guest_name'] ?? null,
                    'payment_method' => $paymentMethod, // CLAIM / CASH / QRIS
                    'total'          => $total,
                    'status'         => $paymentMethod === 'CLAIM' ? 'DONE' : 'PENDING'
                ], true);

                if (!$hkOrderId) {
                    throw new DatabaseException('Failed insert housekeeping order');
                }

                foreach ($payload['items'] as $i) {

                    if (!isset($i['menu_id'])) continue;

                    $hk_menu = $this->hk_menu->find($i['menu_id']);
                    if (!$hk_menu) continue;

                    $qty   = (int) $i['qty'];
                    $price = (float) $i['price'];

                    // 🔥 FIX FK: hk_order_id
                    $this->hk_item->insert([
                        'order_id'    => $hkOrderId,
                        'menu_id'     => $hk_menu['id'],
                        'menu_name'   => $hk_menu['name'],
                        'variant_name'=> $i['variant_name'] ?? null,
                        'qty'         => $qty,
                        'price'       => $price,
                        'subtotal'    => $qty * $price
                    ]);
                }

                $this->db->transComplete();

                return $this->response->setJSON([
                    'status'   => true,
                    'type'     => 'housekeeping',
                    'order_id' => $hkOrderId,
                    'order_no' => $orderNo,
                    'total'    => $total
                ]);
            }

            // ==================================================
            // 🍽️ ROOM SERVICE ORDER
            // ==================================================
            $orderId = $this->order->insert([
                'order_no'       => $orderNo,
                'room_no'        => $payload['room_no'] ?? null,
                'guest_name'     => $payload['guest_name'] ?? null,
                'order_type'     => strtoupper($requestType),
                'payment_method' => $paymentMethod,
                'total'          => $total,
                'status'         => 'PENDING',
                'payment_status' => 'UNPAID'
            ], true);

            if (!$orderId) {
                throw new DatabaseException('Failed insert order');
            }

            foreach ($payload['items'] as $i) {

                if (!isset($i['menu_id'])) continue;

                $menu = $this->menu->find($i['menu_id']);
                if (!$menu) continue;

                $qty   = (int) $i['qty'];
                $price = (float) $i['price'];

                $this->item->insert([
                    'order_id'    => $orderId,
                    'menu_id'     => $menu['id'],
                    'menu_name'   => $menu['name'],
                    'variant_name'=> $i['variant_name'] ?? null,
                    'qty'         => $qty,
                    'price'       => $price,
                    'subtotal'    => $qty * $price
                ]);
            }

            $this->db->transComplete();

            return $this->response->setJSON([
                'status'   => true,
                'type'     => 'room-service',
                'order_id' => $orderId,
                'order_no' => $orderNo,
                'total'    => $total
            ]);

        } catch (\Throwable $e) {

            $this->db->transRollback();

            return $this->response->setJSON([
                'status'  => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }


    /* =====================================================
     * GET ORDER DETAIL
     * GET /api/orders/{order_no}
     * ===================================================== */
    public function show($orderNo)
    {
        $order = $this->order
            ->where('order_no', $orderNo)
            ->first();

        if (!$order) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Order not found'
            ])->setStatusCode(404);
        }

        $items = $this->item
            ->where('order_id', $order['id'])
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'order'  => $order,
            'items'  => $items
        ]);
    }

    /* =====================================================
     * GET ORDERS BY ROOM
     * GET /api/orders/room/{room_no}
     * ===================================================== */
    public function byRoom($roomNo)
    {
        $orders = $this->order
            ->where('room_no', $roomNo)
            ->orderBy('id', 'DESC')
            ->findAll();

        if (!$orders) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No orders found'
            ]);
        }

        foreach ($orders as &$o) {
            $o['items'] = $this->item
                ->where('order_id', $o['id'])
                ->findAll();
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $orders
        ]);
    }
}
