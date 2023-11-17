<?php

namespace App\Models;

use App\Models\Location\Ward;
use App\Models\Location\District;
use App\Models\Location\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talent extends Model
{
    use HasFactory;
    protected $table = 'talents';
    public function skill(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'talent_skill');
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
