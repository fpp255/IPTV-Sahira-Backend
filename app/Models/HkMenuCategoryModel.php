<?php

namespace App\Models;

use CodeIgniter\Model;

class HkMenuCategoryModel extends Model
{
    protected $table            = 'hk_menu_categories';
    protected $primaryKey       = 'id';

    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields = [
        'name',
        'sort_order',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps = true;
}
