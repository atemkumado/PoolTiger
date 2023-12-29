<?php

namespace App\Http\Controllers;

use App\Enums\EnglishLevel;
use App\Http\Resources\TalentResource;
use App\Http\Resources\TalentResourceCollection;
use App\Models\Location\Province;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use App\Http\Requests\TalentRequest;
use App\Services\ProvinceService;
use App\Services\TalentService;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rules\Enum;

class TalentController extends Controller
{
    const KEY_FILTER_CACHE = 'FILTER_CACHE';
    protected TalentService $talentService;
    protected ProvinceService $provinceService;

    public function __construct(TalentService $talentService, ProvinceService $provinceService)
    {
        $this->talentService = $talentService;
        $this->provinceService = $provinceService;
    }

    public function index()
    {
        Cache::clear();
        return view('talents.list');
    }

    public function getData($provinceId)
    {
        $data = $this->provinceService->getProvinceTalents($provinceId);
        Debugbar::info($data);
        return response()->json(['data' => $data]);
    }

    public $data;
    public function viewMore()
    {
        $talents = $this->talentService->getData();
        Debugbar::info($talents);
        return response()->json(['data' => $talents ?? []]);
    }

    public function list(TalentRequest $selected)
    {
        $keyCache = json_encode([self::KEY_FILTER_CACHE, $selected->toArray()]);
//      Check if data exists in the cache
        if (Cache::has($keyCache)) {
            // Data exists in the cache
            $talents = Cache::get($keyCache);
        } else {
            $talents = Cache::remember($keyCache, now()->addHours(), function () use ($selected) {
                return $this->talentService->getTalents($selected);
            });
        }
        Debugbar::info($talents );
        $this->talentService->storeData($talents);
//        $talents = new TalentResourceCollection($talents);
        // Redirect to the form view with a success message
        return view('talents.list', compact(['selected', 'talents']));
    }

    public function detail(string $id)
    {
        $talent = Talent::with(['position:id,name', 'company:id,name', 'province:id,name', 'district:id,name'
            , 'skill' => function ($query) {
                $query->where('is_best', true);
            }
        ])->findOrFail($id);
        $talent['english'] = @Talent::ENGLISH_LEVEL[$talent['english']];
//        return view('talents.detail',['talent' => new TalentResource($talent) ]);
//        return $talent;
        return view('talents.detail', compact('talent'));
    }

    public function loadCRM(){
        $users = DB::connection('crm')->table('vtiger_field')->get();
        dd($users);
    }
}
