<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderChargeTaxModel extends Model
{
    protected $table      = 'order_charge_taxes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'charge_type', 'tax_name', 'tax_percentage', 'tax_amount'];
    protected $useTimestamps = false;

    /**
     * Insert multiple tax rows for a given order + charge type.
     * $taxes = [['tax_name'=>'CGST','tax_percentage'=>9,'tax_amount'=>7.63], ...]
     */
    public function saveTaxes(int $orderId, string $chargeType, array $taxes)
    {
        if (empty($taxes)) return;
        $rows = [];
        foreach ($taxes as $t) {
            $rows[] = [
                'order_id'       => $orderId,
                'charge_type'    => $chargeType,
                'tax_name'       => $t['tax_name'],
                'tax_percentage' => $t['tax_percentage'],
                'tax_amount'     => $t['tax_amount'],
            ];
        }
        $this->insertBatch($rows);
    }

    /**
     * Get all charge tax rows for an order, keyed by charge_type.
     * Returns: ['delivery' => [['tax_name'=>...,'tax_percentage'=>...,'tax_amount'=>...], ...], ...]
     */
    public function getBreakdownByOrder(int $orderId): array
    {
        $rows = $this->where('order_id', $orderId)->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['charge_type']][] = [
                'tax_name'       => $row['tax_name'],
                'tax_percentage' => $row['tax_percentage'],
                'tax_amount'     => $row['tax_amount'],
            ];
        }
        return $result;
    }
}
