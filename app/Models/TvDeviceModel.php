<?php

namespace App\Models;

use CodeIgniter\Model;

class TvDeviceModel extends Model
{
    protected $table      = 'tv_devices';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'device_id',
        'room_no',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps = true;
}
