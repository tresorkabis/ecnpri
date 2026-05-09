<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
    protected $fillable = [
        'user_id', 'name', 'grade', 'employee_id', 'specialization'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspections()
    {
        return $this->belongsToMany(Inspection::class);
    }
}
