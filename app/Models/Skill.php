<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "name"
    ];

    public function talent(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class, 'talent_skill')->withPivot('is_best');
    }

    public function getBestSkill()
    {
        return $this->where('is_best', true)->limit(1);
    }
}
