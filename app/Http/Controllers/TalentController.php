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
use App\Services\TalentService;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Enum;

class TalentController extends Controller
{
    const KEY_CACHE = 'FILTER_CACHE';
    protected $filter = array();
    protected TalentService $talentService;
    public function __construct(TalentService $talentService)
    {
        $this->talentService = $talentService;
    }

    public function index()
    {
        return view('talents.list');
    }

    // Store the form data in the database
    public function list(TalentRequest $selected)
    {
        $keyCache = json_encode([ self::KEY_CACHE, $selected->toArray()]);


//      Check if data exists in the cache
        if (Cache::has($keyCache)) {
            // Data exists in the cache
            $talents = Cache::get($keyCache);
        } else {
            $talents = Cache::remember($keyCache, now()->addHours(), function () use ($selected){
                return $this->talentService->getTalents($selected);
            });
        }


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
}
