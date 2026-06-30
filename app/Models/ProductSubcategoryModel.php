<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductSubcategoryModel extends Model
{
    protected $table            = 'product_subcategories';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';

    protected $allowedFields    = [
        'product_id',
        'subcategory_id'
    ];
}
