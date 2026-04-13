<?php

namespace App\Models;

use CodeIgniter\Model;

class HkorderItemModel extends Model
{
    protected $table      = 'hk_order_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'order_id','menu_id','menu_name',
        'variant_name','qty',
        'price','notes','subtotal'
    ];

    // INI WAJIB DIMATIKAN
    protected $useTimestamps = false;
}

