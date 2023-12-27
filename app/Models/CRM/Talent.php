<?php

namespace App\Models\CRM;

use App\Models\Location\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Talent extends Model
{
    use HasFactory;
    protected $connection = 'crm';

}
