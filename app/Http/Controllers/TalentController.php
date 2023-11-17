<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use App\Models\Location\Province;
use App\Http\Requests\TalentRequest;

class TalentController extends Controller
{
    const skill = ["PHP", "Java", "C#", "JS", "NodeJs", "C++"];
    protected $provinces;
    protected $skill;

    public function __construct()
    {
        $this->provinces = Province::pluck('name','id');
        $this->skill = Skill::pluck('name','id');

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
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];
        return view('talents.filter', ['filter' => $filter]);
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
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];

        $input = new Talent();
        $skillId = $request->skill;
        $teasd = Talent::with(['skill' => function ($query) use ($skillId) {
            $query->where('skill_id', $skillId);
            }])->get()->toArray();
        // $talents =  Talent::with('skill')->where('english', 5)->get()->toArray();

        $skill = new Skill();
        // Assign the request data to the user attributes
        // $input->province = $request->province ?? '';
        // $input->skill = $request->skill ?? '';
        // $input->year = $request->year ?? '';
        // $input->position = $request->position ?? '';
        // $input->salary = $request->salary ?? '';
        // Save the user in the database
        dd($teasd);


        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $filter, 'input' => $input]);
    }

    public function detail()
    {
        return view('talents.detail');
    }
}
