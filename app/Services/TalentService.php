<?php

namespace App\Services;

use App\Http\Requests\TalentRequest;
use App\Models\Location\Province;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TalentService
{
    public function __construct()
    {
        Log::debug("TALENT SERVICE");

    }

    public function getTalents(TalentRequest $request): Collection|array
    {
        $talents = Talent::query();
        if (!is_null($request->province)) {
            $talents->where('province_id', $request->province);
        }
        if (!is_null($request->english)) {
            $talents->where('english', $request->english);
        }
        if (!is_null($request->skill)) {
            $talents->whereHas('skill', function ($query) use ($request) {
                $query->where('is_best', true)->where('skill_id', $request->skill);
            });
        }
        if (!is_null($request->position)) {
            $talents->whereHas('position', function ($query) use ($request) {
                $query->where('position_id', $request->position);
            });
        }

        if (!is_null($request->salary)) {
            $talents->where('salary','<=', $request->salary);
        }

        if (!is_null($request->experience)) {
            $talents->where('experience','>=', $request->experience);
        }
        // get company information
        $talents->with(['company:id,name,province_id', 'company.province', 'province:id,name', 'position:id,name', 'skill']);
        // Normalize the data of talents

        $talents = $talents->get();
        $talents->map(function ($talent) {
            $talent->company_name = @$talent->company['name'];
            $talent->province_company_name = @$talent->company->province["name"];
            $talent->province_name = @$talent->province['name'];
            $talent->skill_name = @$talent->skill[0]['name'];
            $talent->position_name = @$talent->position[0]['name'];
            return $talent;
        });
        Log::debug("GET_TALENT");
        return $talents;
    }

    public static function getFilter()
    {
        Log::debug("GET_INPUT");
        return [
            'province' => Province::pluck('name', 'id') ?? [''],
            'skill' => Skill::pluck('name', 'id') ?? [''],
            'experience' => [''],
            'position' => Position::pluck('name', 'id') ?? [''],
            'english' => Talent::getEnglishes() ?? [''],
            'salary' => null
        ];
    }
    const KEY_VIEW_CACHE = 'VIEW_CACHE';

    public function storeData($value)
    {
        Cache::put(self::KEY_VIEW_CACHE, $value, now()->addHours());
    }

    public function getData()
    {
        return Cache::get(self::KEY_VIEW_CACHE);
    }
}
