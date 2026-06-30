<?php 
namespace App\Models;

use CodeIgniter\Model;

class PosCartSessionModel extends Model
{
    protected $table = 'pos_cart_sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'session_id', 'created_by_admin', 'seller_id', 'user_id', 'customer_name', 
        'customer_mobile', 'cart_data', 'additional_discount', 
        'additional_discount_type', 'additional_charges', 
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
