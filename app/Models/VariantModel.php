<?php

namespace App\Models;

use CodeIgniter\Model;

class VariantModel extends Model
{
    protected $table = 'menu_variants';
    protected $allowedFields = ['menu_id', 'name', 'price'];
}
