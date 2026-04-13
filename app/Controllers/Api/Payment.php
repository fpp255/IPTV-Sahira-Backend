<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use Midtrans\Notification;

class Payment extends BaseController
{
    public function notification()
    {
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$payload) {
            return $this->response->setStatusCode(400);
        }

        $orderNo     = $payload['order_id'] ?? null;
        $status      = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $trxId       = $payload['transaction_id'] ?? null;
        $fraud       = $payload['fraud_status'] ?? null;

        $orderModel = new OrderModel();

        $order = $orderModel->where('order_no', $orderNo)->first();

        if (!$order) {
            log_message('error', 'ORDER NOT FOUND: '.$orderNo);
            return $this->response->setStatusCode(404);
        }

        // =========================
        // SUCCESS PAYMENT
        // =========================
        if (
            $status === 'settlement' ||
            ($status === 'capture' && $fraud === 'accept')
        ) {
            $orderModel->update($order['id'], [
                'payment_status'            => 'PAID',
                'payment_method'            => strtoupper($paymentType),
                'midtrans_order_id'         => $orderNo,
                'midtrans_transaction_id'   => $trxId,
                'status'                    => 'PENDING'
            ]);
        }

        // =========================
        // PENDING
        // =========================
        if ($status === 'pending') {
            $orderModel->update($order['id'], [
                'payment_status' => 'PENDING'
            ]);
        }

        // =========================
        // FAILED
        // =========================
        if (in_array($status, ['expire','deny','cancel'])) {
            $orderModel->update($order['id'], [
                'payment_status' => 'FAILED',
                'status'         => 'CANCEL'
            ]);
        }

        return $this->response->setJSON(['status' => true]);
    }
}