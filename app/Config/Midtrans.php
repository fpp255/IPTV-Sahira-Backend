<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey;
    public bool $isProduction;

    public function __construct()
    {
        parent::__construct();

        $this->serverKey    = env('MIDTRANS_SERVER_KEY');
        $this->clientKey    = env('MIDTRANS_CLIENT_KEY');
        $this->isProduction = env('MIDTRANS_PRODUCTION', false);
    }
}
