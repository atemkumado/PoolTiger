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

    const ID = [
        'ha_noi' => 1,
        'da_nang' => 48,
        'ho_chi_minh' => 79,
    ];

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

    public static function get3ProvincesStatistic(): array
    {
        $statistic =  self::selectRaw('provinces.id, provinces.name, count(*) as count')
            ->join('talents', 'provinces.id', '=', 'talents.province_id')
            ->whereIn('province_id', array_values(self::ID))
            ->groupBy('provinces.id','provinces.name')
            ->get()->keyBy->id->toArray();

        $otherCount = Talent::count() - $statistic[self::ID['ha_noi']]['count'] - $statistic[self::ID['da_nang']]['count']
            - $statistic[self::ID['ho_chi_minh']]['count'] ;

        $statistic[0] = [
            'id' => false,
            'name' => 'Other',
            'count' => $otherCount
        ];
        return $statistic;
    }
}
