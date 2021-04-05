<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentiRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'encountered_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'tagged_med_at' => 'datetime',
    ];

    public function getCleanVitalSignsAttribute()
    {
        return $this->vital_signs == 'T:  | PR:  | RR:  | BP: / | O2: %' ? null : $this->vital_signs;
    }

    public function getCleanTriageAttribute()
    {
        if (! $this->triage) {
            return null;
        }
        $triage = trim($this->triage, '|');
        $triage = str_replace(' | | ', ' | ', $triage);
        $fields = explode(' | ', $triage);
        $triage = [];
        foreach ($fields as $field) {
            if (strpos($field, 'วิธีมาโรงพยาบาล') !== false) {
                $triage['via'] = trim(explode(':', $field)[1]);
            } elseif (strpos($field, 'ระดับความรุนแรง') !== false) {
                $triage['severity'] = trim(explode(':', $field)[1]);
            } elseif (strpos($field, 'วิธีการเคลื่อนย้าย') !== false) {
                $triage['mobility'] = trim(explode(':', $field)[1]);
            }
        }

        return $triage;
    }
}
