<?php

namespace App\Http\Controllers;

use App\Enums\EnglishLevel;
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
        })->with('position', function ($query) use ($request) {
            if ($request->position) {
                return $query->where('position_id', $request->position);
            }
            return false;
        })->with('company:id,name')
            ->get()->keyBy->id;

        // $talents =  Talent::with('skill')->where('english', 5)->get()->toArray();
        // dd(array_map(fn($level) => $level->value, EnglishLevel::cases())) ;
//        return $talents;
//        return Talent::with('skill:name')->with('position:name')->get()->keyBy->id;
//        $talents = Talent::get();
//        $talents = Talent::all();
        $list = new TalentResourceCollection($talents);
        // Assign the request data to the user attributes
//        return $list;

        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $data, 'selected' => $request, 'talents' => $list]);
    }

    public function detail()
    {
        return view('talents.detail');
    }
}
