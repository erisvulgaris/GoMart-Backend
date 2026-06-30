<?php

namespace App\Models;

use CodeIgniter\Model;

class AdditionalChargeTaxModel extends Model
{
    protected $table      = 'additional_charge_taxes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tax_id', 'is_active'];
    protected $useTimestamps = false;

    /**
     * Get all active additional charge taxes joined with tax details.
     */
    public function getActiveTaxes()
    {
        return $this->db->table('additional_charge_taxes act')
            ->select('act.id, act.tax_id, t.tax as tax_name, t.percentage as tax_percentage')
            ->join('tax t', 't.id = act.tax_id', 'inner')
            ->where('act.is_active', 1)
            ->where('t.is_active', 1)
            ->where('t.is_delete', 0)
            ->get()
            ->getResultArray();
    }

    /**
     * Replace all additional charge tax config with new set of tax_ids.
     */
    public function syncTaxes(array $taxIds)
    {
        $this->db->table('additional_charge_taxes')->truncate();
        if (!empty($taxIds)) {
            $rows = [];
            foreach ($taxIds as $taxId) {
                $rows[] = ['tax_id' => (int)$taxId, 'is_active' => 1];
            }
            $this->insertBatch($rows);
        }
    }
}
