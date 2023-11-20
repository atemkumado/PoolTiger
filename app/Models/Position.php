<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    use HasFactory;

    public function talent(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class, 'talent_position');
    }
}
