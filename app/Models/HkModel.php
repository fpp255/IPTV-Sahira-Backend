<?php

namespace App\Models;

use CodeIgniter\Model;

class HkModel extends Model
{
    protected $table      = 'hk';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'category_id',
        'name',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
