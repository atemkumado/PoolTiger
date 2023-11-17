<?php

namespace App\Models;

use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Talent extends Model
{
    use HasFactory;
    protected $table = 'talents';
    public function skill()
    {
        return $this->belongsToMany(Skill::class);
    }

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
    

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($talent) {
            // do some extra stuff before deleting
           
        });
    }
}
