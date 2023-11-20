<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    
    
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    
    
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($company) {
            // do some extra stuff before deleting
           $company->talent()->detach();
        });
    }
}
