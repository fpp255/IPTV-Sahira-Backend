<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'menu_categories';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'status'];

    // Helper khusus kategori aktif
    public function getActive()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
