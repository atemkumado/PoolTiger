<?php

namespace App\Models;

use App\Enums\EnglishLevel;
use App\Http\Requests\TalentRequest;
use App\Models\Location\Ward;
use App\Models\Location\District;
use App\Models\Location\Province;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Talent extends Model
{
    use HasFactory;

    protected $table = 'talents';
    public const ENGLISH_LEVEL_TITLE = [
        'No English' => 0,
        'Basic' => 5,
        'Intermediate' => 10,
        'Advanced' => 15,
         'Fluently' => 20,
    ];
    public const ENGLISH_LEVEL = [
        0 => 'No English',
        5 => 'Basic',
        10 => 'Intermediate',
        15 => 'Advanced',
        20 => 'Fluently',
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

    public static function getEnglishes()
    {
        $data = [];

        foreach (EnglishLevel::cases() as $value) {
            $name = ucfirst(str_replace('_', " ", strtolower($value->name)));
            $data[$value->value] = $name;
        }
        return $data;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($talent) {
            // do some extra stuff before deleting

        });
    }
}
