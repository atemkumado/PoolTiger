<?php

namespace App\Http\Controllers;

use App\Models\Talent;
use App\Models\Location\Province;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;

class TalentController extends Controller
{
    const ABILITY = ["PHP", "Java", "C#", "JS", "NodeJs", "C++"];
    protected $provinces;
    public function __construct()
    {
        $this->provinces = Province::pluck("name")->toArray();
    }

    public function index()
    {
        // Return the view with the countries data

        // Todo: Get data from database table
        $filter = [
            'city' => $this->provinces,
            'ability' => self::ABILITY,
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];
        return view('talents.filter', ['filter' => $filter]);
    }

    // Store the form data in the database
    public function getList(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'province' => 'required',
        // ]);

        // Create a new user instance
        $filter = [
            'city' => $this->provinces,
            'ability' => self::ABILITY,
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];

        $input = new Talent();
        // Assign the request data to the user attributes
        $input->city = $request->city ?? '';
        $input->ability = $request->ability ?? '';
        $input->yoe = $request->yoe ?? '';
        $input->position = $request->position ?? '';
        $input->salary = $request->salary ?? '';
        // Save the user in the database


        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $filter, 'input' => $input]);
    }

    public function getProfile()
    {
        return view('talents.profile');
    }
}
