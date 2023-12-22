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

    const ID = [
        'ha_noi' => 1,
        'da_nang' => 48,
        'ho_chi_minh' => 79,
//        'other' => 0
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
        $statistic = self::selectRaw('provinces.id, provinces.name, count(*) as count')
            ->join('talents', 'provinces.id', '=', 'talents.province_id')
            ->whereIn('province_id', array_values(self::ID))
            ->groupBy('provinces.id', 'provinces.name')
            ->get()->keyBy->id->toArray();

        $otherCount = Talent::count() - $statistic[self::ID['ha_noi']]['count'] - $statistic[self::ID['da_nang']]['count']
            - $statistic[self::ID['ho_chi_minh']]['count'];

        $statistic[0] = [
            'id' => false,
            'name' => 'Other',
            'count' => $otherCount
        ];
        return $statistic;
    }

    public static function getProvinceTalents($provinceId)
    {
        if (!$provinceId) {
            $data = Talent::whereNotIn('province_id', [
                Province::ID['ha_noi'], Province::ID['da_nang'], Province::ID['ho_chi_minh']
            ])
                ->with(['company:id,name,province_id', 'company.province', 'position:id,name', 'province:id,name', 'skill' => function ($query) {
                    $query->where('is_best', true)->get(['skills.id', 'skills.name']);
                }])->get()
                ->map(function ($talent) {
                    $talent->province_name = @$talent->province['name'];
                    $talent->skill_name = @$talent->skill[0]['name'];
                    $talent->position_name = @$talent->position[0]['name'];
                    return $talent;
                })->toArray();
        } else {
            $data = self::find($provinceId)
                ->talent()
                ->with(['company:id,name,province_id', 'company.province', 'position:id,name', 'province:id,name', 'skill' => function ($query) {
                    $query->where('is_best', true)->get(['skills.id', 'skills.name']);
                }])->get()
                ->map(function ( $talent) {
                    $talent->province_name = @$talent->province['name'];
                    $talent->skill_name = @$talent->skill[0]['name'];
                    $talent->position_name = @$talent->position[0]['name'];
                    return $talent;
                })->toArray();
        }
//        dd($data);

//            $data[$provinceId] = Talent::whereHas('province', function ($query) use ($provinceId) {
//                if ($provinceId == 0) {
//                    $query->whereNotIn('province_id', [
//                        Province::ID['ha_noi'], Province::ID['da_nang'], Province::ID['ho_chi_minh']
//                    ]);
//                } else {
//                    $query->where('province_id', $provinceId);
//                }
//            })
//                ->with(['company:id,name,province_id', 'company.province', 'position:id,name', 'province:id,name', 'skill' => function ($query) {
//                    $query->where('is_best', true)->get(['skills.id', 'skills.name']);
//                }])->get()
//                ->map(function (Talent $talent) {
//                    $talent->province_name = @$talent->province['name'];
//                    $talent->skill_name = @$talent->skill[0]['name'];
//                    $talent->position_name = @$talent->position[0]['name'];
//                    return $talent;
//                });

        return $data ?? [];
    }
}
