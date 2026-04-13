<?php

namespace App\Models;

use CodeIgniter\Model;

class GuestModel extends Model
{
    protected $table      = 'guests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'device_id',
        'room_no',
        'guest_name',
        'status',
        'checkin_date',
        'checkout_date',
        'created_by'
    ];

    protected $useTimestamps = true;

    // SOFT DELETE
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
}
