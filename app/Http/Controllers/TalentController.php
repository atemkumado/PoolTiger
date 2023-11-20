<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Talent;
use App\Models\Position;
use App\Enums\EnglishLevel;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use App\Models\Location\Province;
use App\Http\Requests\TalentRequest;

class TalentController extends Controller
{
    protected $provinces;
    protected $skill;
    protected $position;
    protected $talent;

    public function __construct()
    {
        $this->provinces = Province::pluck('name', 'id');
        $this->skill = Skill::pluck('name', 'id');
        $this->position = Position::pluck('name', 'id');
        $this->talent = new Talent();
        // dd($this->talent->getEnglishes());
        // dd(Position::pluck('name', 'id'));
    }

    public function index()
    {
        // Return the view with the countries data
        // dd($this->provinces);
        // Todo: Get data from database table
        $filter = [
            'province' => $this->provinces,
            'skill' =>  $this->skill,
            'experience' => [''],
            'position' => $this->position,
            'english' => $this->talent->getEnglishes(),
            'salary' => ['']
        ];
        $input = new Talent();
        return view('talents.list', ['filter' => $filter, 'input' => $input]);
    }

    // Store the form data in the database
    public function list(TalentRequest $request)
    {
        // Validate the request data
        // dd($request->input("province"));
        // Create a new user instance
        $filter = [
            'province' => $this->provinces,
            'skill' => $this->skill,
            'experience' => [''],
            'position' => $this->position,
            'english' => $this->talent->getEnglishes(),
            'salary' => ['']
        ];

        $input = new Talent();

        $province = $request->province;
        $skillId = $request->skill;
        // $result = Talent::with(['skill' => function ($query) use ($skillId) {
        //     return $query->where('skill_id', $skillId) ;
        //     }])->has('skill')->get()->toArray();
        $result = Talent::with('skill')->whereHas('skill', function ($query) use ($request) {
            if($request->province){
                $query->where('province_id', $request->province);
            }
            if($request->skill){
                $query->where('skill_id', $request->skill);
            }
        })->with('position')->whereHas('position', function ($query) use ($request) {
            if($request->position){
                $query->where('position_id', $request->position);
            }
        })->get()->toArray();
        // $talents =  Talent::with('skill')->where('english', 5)->get()->toArray();
        // dd(array_map(fn($level) => $level->value, EnglishLevel::cases())) ;
        $skill = new Skill();
        // Assign the request data to the user attributes
        // $input->province = $request->province ?? '';
        // $input->skill = $request->skill ?? '';
        // $input->year = $request->year ?? '';
        // $input->position = $request->position ?? '';
        // $input->salary = $request->salary ?? '';
        // Save the user in the database
      


        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $filter, 'input' => $input]);
    }

    public function detail()
    {
        return view('talents.detail');
    }
}
