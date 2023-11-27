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

    public function index($data = null)
    {
        return view('talents.list', ['filter' => $data]);
    }

    // Store the form data in the database
    public function list(TalentRequest $request, $data = null)
    {
        $request->validate([
//            'english' => [new Enum(EnglishLevel::class)],
        ]);
        // Validate the request data
//         dd($request->english);
        // Create a new user instance

        // $result = Talent::with(['skill' => function ($query) use ($skillId) {
        //     return $query->where('skill_id', $skillId) ;
        //     }])->has('skill')->get()->toArray();
        $talents = Talent::with('skill')->whereHas('skill', function ($query) use ($request) {
            if (!is_null($request->province)) {
                $query->where('province_id', $request->province);
            }
            if (!is_null($request->skill)) {
                $query->where('skill_id', $request->skill);
            }
            if (!is_null($request->english)) {
                $query->where('english', $request->english);
            }
        })->with('position')->whereHas('position', function ($query) use ($request) {
            if (!is_null($request->position)) {
                $query->where('position_id', $request->position);
            }
        })->with('company:id,name')
            ->get()->keyBy->id;


        $list = new TalentResourceCollection($talents);
        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $data, 'selected' => $request, 'talents' => $list]);
    }

    public function detail(string $id)
    {
        $talent = Talent::with('skill')->with('province')->with('position')->with('company')->findOrFail($id);
        $talent = new TalentResource($talent);
//        return view('talents.detail',['talent' => new TalentResource($talent) ]);
//        return $talent;
        return view('talents.detail',['talent' => $talent ]);
    }
}
