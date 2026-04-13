<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use Midtrans\Config;
use Midtrans\Snap;

class Payment extends BaseController
{
    public function midtransLink($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Order not found'
            ]);
        }

        // ==========================
        // MIDTRANS CONFIG
        // ==========================
        Config::$serverKey = config('Midtrans')->serverKey;
        Config::$isProduction = config('Midtrans')->isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ==========================
        // WAJIB ORDER ID UNIK
        // ==========================
        $midtransOrderId = $order['order_no'] . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $order['order_no'],
                'gross_amount' => (int)$order['total']
            ],
            'customer_details' => [
                'first_name' => $order['guest_name'] ?: 'Guest',
                'email' => 'guest@sahirahotel.com',
                'phone' => '08123456789'
            ],
            'enabled_payments' => [
                'gopay',
                'qris',
                'bank_transfer',
                'shopeepay',
                'credit_card',
                'akulaku',
                'kredivo',
                'dana'
            ],
            'callbacks' => [
                // 'finish' => 'https://api.salamdjourney.com/restapi/payment-finish',
                // 'error'    => 'https://api.salamdjourney.com/restapi/payment-error',
                'finish'   => base_url('payment-finish'),
                'error'    => base_url('admin/payment/error'),
                'pending'  => base_url('admin/payment/pending')
            ]
        ];

        try {
            $snap = Snap::createTransaction($params);

            return $this->response->setJSON([
                'status' => true,
                'payment_url' => $snap->redirect_url
            ]);

        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function finish()
    {
        return view('paymentfinish');
    }

    public function pending()
    {
        return view('admin/payment/pending');
    }

    public function error()
    {
        return view('admin/payment/error');
    }
}
