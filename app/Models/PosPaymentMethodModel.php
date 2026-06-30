<?php

namespace App\Models;

use CodeIgniter\Model;

class PosPaymentMethodModel extends Model
{
    protected $table = 'pos_payment_method';
    protected $primaryKey = 'id';
    
    protected $allowedFields = ['name']; // Add more fields if needed
}
