<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'order_no','room_no','guest_name',
        'order_type','source_device','status',
        'payment_status','payment_method','midtrans_order_id',
        'midtrans_transaction_id','total','created_by','updated_by','canceled_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
}
