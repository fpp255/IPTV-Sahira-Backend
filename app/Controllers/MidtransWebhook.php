<?php

namespace App\Controllers;

use App\Models\OrderModel;
use Midtrans\Notification;

class MidtransWebhook extends BaseController
{
    public function index()
    {
        $notif = new Notification();

        $orderNo = $notif->order_id;
        $status  = $notif->transaction_status;

        if (in_array($status, ['settlement', 'capture'])) {
            $orderModel = new OrderModel();
            $orderModel->where('order_no', $orderNo)->set([
                'payment_status' => 'PAID',
                'payment_method' => 'MIDTRANS'
            ])->update();
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function webhook()
	{
	    $notif = new Notification();
	    $orderNo = $notif->order_id;
	    $status  = $notif->transaction_status;
	    $payment = $notif->payment_type;

	    $order = $this->order
	        ->where('order_no', $orderNo)
	        ->first();

	    if (!$order) {
	        return $this->response->setStatusCode(404);
	    }

	    if (in_array($status, ['capture','settlement'])) {
	        $this->order->update($order['id'], [
	            'payment_status' => 'PAID',
	            'payment_method' => strtoupper($payment),
	            'status'         => 'PENDING'
	        ]);
	    }

	    return $this->response->setJSON(['status'=>'ok']);
	}
}