<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table      = 'menus';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name', 'price', 'category_id', 'image', 'is_roomservice', 'is_active', 'created_by', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
    ];

    // HANYA MENU AKTIF
    public function getActive($categoryId = null)
    {
        $builder = $this->where('is_active', 1);

        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }

        return $builder->orderBy('name', 'ASC')->findAll();
    }
}
