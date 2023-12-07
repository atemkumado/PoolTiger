<?php

namespace App\Models\Location;

use App\Models\Company;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    const HA_NOI_ID = 1;
    const DA_NANG_ID = 48;
    const HCM_ID = 79;


    public function talent(): hasMany
    {
        return $this->hasMany(Talent::class);
    }
    public function company(): hasMany
    {
        return $this->hasMany(Company::class);
    }
    public function district(): hasMany
    {
        return $this->hasMany(District::class);
    }
}
