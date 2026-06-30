<?php
namespace App\Models;

use CodeIgniter\Model;

class OrderAdditionalChargeModel extends Model
{
    protected $table = 'order_additional_charges';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'charge_name', 'charge_amount', 'tax_name', 'tax_percentage', 'tax_amount', 'created_at'];
    protected $useTimestamps = false;
}