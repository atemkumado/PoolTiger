<?php

namespace App\Models;

use App\Enums\EnglishLevel;
use App\Models\Location\Ward;
use App\Models\Location\District;
use App\Models\Location\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Talent extends Model
{
    use HasFactory;
    protected $table = 'talents';
    public const ENGLISH_LEVEL = [
        'NO_ENGLISH' => 0,
        'BASIC' => 5,
        'INTERMEDIATE' => 10,
        'ADVANCED' => 15,
        'FLUENTLY' => 20,
    ];
    public function skill(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'talent_skill');
    }

    public function position(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'talent_position');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
    public static  function getEnglishes()
    {
        $data = [];

        foreach (EnglishLevel::cases() as $value) {
            $name =  ucfirst(str_replace('_', " ", strtolower( $value->name)));
            $data[$value->value] = $name;
        }
        return $data;
    }

    public static function getFilter()
    {
        return [
            'province' => Province::pluck('name', 'id') ?? [''],
            'skill' =>  Skill::pluck('name', 'id') ?? [''],
            'experience' => [''],
            'position' => Position::pluck('name', 'id') ?? [''],
            'english' => self::getEnglishes() ?? [''],
            'salary' => null
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($talent) {
            // do some extra stuff before deleting

        });
    }
}
