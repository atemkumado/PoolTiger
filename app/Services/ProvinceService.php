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
        Log::debug("SHESSS");
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
            $data = Province::find($provinceId)
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
        Log::debug('GET');
        return $data ?? [];
    }

    public static function get3ProvincesStatistic(): array
    {
        $statistic = Province::selectRaw('provinces.id, provinces.name, count(*) as count')
            ->join('talents', 'provinces.id', '=', 'talents.province_id')
            ->whereIn('province_id', array_values(Province::ID))
            ->groupBy('provinces.id', 'provinces.name')
            ->get()->keyBy->id->toArray();

        $otherCount = Talent::count() - $statistic[Province::ID['ha_noi']]['count'] - $statistic[Province::ID['da_nang']]['count']
            - $statistic[Province::ID['ho_chi_minh']]['count'];

        $statistic[0] = [
            'id' => false,
            'name' => 'Other',
            'count' => $otherCount
        ];
        Log::debug('INIT');
        return $statistic;
    }
}