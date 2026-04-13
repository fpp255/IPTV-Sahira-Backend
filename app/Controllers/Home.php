<?php

namespace App\Controllers;
use App\Controllers\BaseController;
class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/login');
    }

    public function paymentFinish()
    {
        return view('paymentfinish');
    }
}



