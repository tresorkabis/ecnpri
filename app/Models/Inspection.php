<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'establishment_id', 'team_leader_id', 'start_date', 'end_date', 'type', 'purpose', 'status', 'authorized_by', 'summary', 'methodology', 'conclusion', 'site_representative', 'site_representative_title', 'report_path'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 0;
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function teamLeader()
    {
        return $this->belongsTo(Inspector::class, 'team_leader_id');
    }

    public function inspectors()
    {
        return $this->belongsToMany(Inspector::class);
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
