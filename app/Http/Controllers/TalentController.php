<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Talent;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use App\Models\Location\Province;
use App\Http\Requests\TalentRequest;

class TalentController extends Controller
{
    const skill = ["PHP", "Java", "C#", "JS", "NodeJs", "C++"];
    protected $provinces;
    public function __construct()
    {
        $this->provinces = Province::pluck('name','id');
    }

    public function index()
    {
        // Return the view with the countries data
        // dd($this->provinces);
        // Todo: Get data from database table
        $filter = [
            'city' => $this->provinces,
            'skill' => self::skill,
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
        // dd($request->input("city"));
        // Create a new user instance
        $filter = [
            'city' => $this->provinces,
            'skill' => self::skill,
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];

        $input = new Talent();
        dd(Talent::where('province_id',$request->city)->where('english',5)->get()->toArray());
        // Assign the request data to the user attributes
        $input->city = $request->city ?? '';
        $input->skill = $request->skill ?? '';
        $input->year = $request->year ?? '';
        $input->position = $request->position ?? '';
        $input->salary = $request->salary ?? '';
        // Save the user in the database


        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $filter, 'input' => $request]);
    }

    public function detail()
    {
        return view('talents.detail');
    }
}
