<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\MenuModel;

class Orders extends BaseController
{
    protected $order;
    protected $item;
    protected $menu;
    protected $db;

    public function __construct()
    {
        $this->order = new OrderModel();
        $this->item  = new OrderItemModel();
        $this->menu  = new MenuModel();
        $this->db    = \Config\Database::connect();
    }


