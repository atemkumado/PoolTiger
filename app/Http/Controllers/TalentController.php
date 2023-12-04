<?php

namespace App\Http\Controllers;

use App\Enums\EnglishLevel;
use App\Http\Resources\TalentResource;
use App\Http\Resources\TalentResourceCollection;
use App\Models\Skill;
use App\Models\Talent;
use App\Http\Requests\TalentRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;

class TalentController extends Controller
{
    protected $filter = array();

    public function __construct()
    {
        //   $this->filter = Talent::getFilter();

    }

    public function index()
    {
        return view('talents.list');
    }

    // Store the form data in the database
    public function list(TalentRequest $selected)
    {
        $selected->validate([
//            'english' => [new Enum(EnglishLevel::class)],
        ]);
        // Validate the request data
//         dd($request->english);

        $talents = Talent::whereHas('skill', function ($query) use ($selected) {
            if (!is_null($selected->province)) {
                $query->where('province_id', $selected->province);
            }
            if (!is_null($selected->skill)) {
                $query->where('is_best',true)->where('skill_id', $selected->skill);
            }
            if (!is_null($selected->english)) {
                $query->where('english', $selected->english);
            }
        })
            ->whereHas('position', function ($query) use ($selected) {
                if (!is_null($selected->position)) {
                    $query->where('position_id', $selected->position);
                }
            })
            ->with(['company:id,name,province_id', 'company.province'])
            ->get()->keyBy->id;

//        $list = new TalentResourceCollection($talents);
        // Redirect to the form view with a success message
        return view('talents.list', compact(['selected', 'talents']));
    }

    public function detail(string $id)
    {
        $talent = Talent::with(['position:id,name', 'company:id,name', 'province:id,name', 'district:id,name'
          , 'skill' => function ($query) {
                $query->where('is_best',true);
            }
        ])->findOrFail($id);
        $talent['english'] = @Talent::ENGLISH_LEVEL[ $talent['english']];
//        return view('talents.detail',['talent' => new TalentResource($talent) ]);
//        return $talent;
        return view('talents.detail', compact('talent'));
    }
}
