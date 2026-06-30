<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionHighlightModel extends Model
{
    protected $table      = 'section_highlights';
    protected $primaryKey = 'id';
    protected $allowedFields = ['section_id', 'highlight_id', 'sort_order'];
    protected $returnType = 'array';

    public function getHighlightsBySectionId($sectionId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('section_highlights.*, highlights.title, highlights.image')
                ->join('highlights', 'highlights.id = section_highlights.highlight_id', 'left')
                ->where('section_highlights.section_id', $sectionId)
                ->orderBy('section_highlights.id', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function syncHighlights($sectionId, $highlightIds)
    {
        $this->where('section_id', $sectionId)->delete();
        if (!empty($highlightIds)) {
            $data = [];
            foreach ($highlightIds as $index => $hlId) {
                $data[] = [
                    'section_id'   => $sectionId,
                    'highlight_id' => $hlId,
                    'sort_order'   => $index,
                ];
            }
            $this->insertBatch($data);
        }
        return true;
    }
}
