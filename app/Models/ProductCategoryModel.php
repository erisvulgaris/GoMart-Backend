<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table            = 'product_categories';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';

    protected $allowedFields    = [
        'product_id',
        'category_id'
    ];
}
