<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TalentSkill extends Model
{
    use HasFactory;

    protected $table = 'talent_skill';

    protected $fillable = ['talent_id', 'skill_id', 'is_best'];
}
