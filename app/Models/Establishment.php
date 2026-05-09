<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    protected $fillable = [
        'name', 'address', 'province', 'city', 'contact_name', 'contact_title', 'phone', 'email', 'category',
        'rpr_name', 'rpr_phone', 'rpr_email', 'rpr_accreditation'
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function radioactiveSources()
    {
        return $this->hasMany(RadioactiveSource::class);
    }

    public function usageAuthorizations()
    {
        return $this->hasMany(UsageAuthorization::class);
    }
}
