<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuVariantModel extends Model
{
    protected $table            = 'menu_variants';
    protected $primaryKey       = 'id';

    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields = [
        'menu_id',
        'name',
        'price',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
