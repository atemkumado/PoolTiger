<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    public function talent(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class, 'talent_skill');
    }
    public function getBestSKill()
    {
        return $this->where('is_best',true)->limit(1);
    }
}
