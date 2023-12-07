<?php

namespace App\Models;

use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }


    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }


    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function talents(): HasMany
    {
        return $this->hasMany(Talent::class);
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
