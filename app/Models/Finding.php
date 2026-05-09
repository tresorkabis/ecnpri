<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    protected $fillable = [
        'inspection_id', 'description', 'severity', 'recommendation', 'deadline', 'status'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
