<?php

namespace App\Services;

use App\Models\Location\Province;
use App\Models\Talent;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Log;

class ProvinceService
{
    public function __construct()
    {
        Log::debug("PROVINCE SERVICE");
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
                    $talent->company_name = @$talent->company['name'];
                    $talent->province_name = @$talent->province['name'];
                    $talent->skill_name = @$talent->skill[0]['name'];
                    $talent->position_name = @$talent->position[0]['name'];
                    return $talent;
                })->toArray();
        } else {
            $data = Province::find($provinceId)
                ->talent()
                ->with(['company:id,name,province_id', 'company.province', 'position:id,name', 'province:id,name', 'skill' => function ($query) {
                    $query->where('is_best', true)->get(['skills.id', 'skills.name']);
                }])->get()
                ->map(function ( $talent) {
                    $talent->company_name = @$talent->company['name'];
                    $talent->province_name = @$talent->province['name'];
                    $talent->skill_name = @$talent->skill[0]['name'];
                    $talent->position_name = @$talent->position[0]['name'];
                    return $talent;
                })->toArray();
        }
        Log::debug('GET');
        return $data ?? [];
    }

    public static function get3ProvincesStatistic(): array
    {
        $statistic = Province::selectRaw('
            provinces.id,
            provinces.name,
            SUM(IF(talents.province_id IS NOT NULL, 1, 0)) AS count'
        )
            ->leftjoin('talents', 'provinces.id', '=', 'talents.province_id')
            ->whereIn('provinces.id', array_values(Province::ID))
            ->groupBy('provinces.id', 'provinces.name')
            ->get()->keyBy->id->toArray();

        $otherCount = Talent::whereHas('province')->count() - $statistic[Province::ID['ha_noi']]['count'] - $statistic[Province::ID['da_nang']]['count']
            - $statistic[Province::ID['ho_chi_minh']]['count'];

        $statistic[0] = [
            'id' => 0,
            'name' => 'Other',
            'count' => $otherCount
        ];
        Log::debug($statistic);
        return $statistic;
    }
}
