<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        // Return the view with the countries data

        // Todo: Get data from database table
        $filter = [
            'city' => [''],
            'ability' => [''],
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];
        return view('lead.filter', ['filter' => $filter]);
    }

    // Store the form data in the database
    public function list(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'province' => 'required',
        // ]);

        // Create a new user instance
        $filter = [
            'city' => [''],
            'ability' => [''],
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];

        $input = new Leads();
        // Assign the request data to the user attributes
        $input->city = $request->city ?? '';
        $input->ability = $request->ability ?? '';
        $input->yoe = $request->yoe ?? '';
        $input->position = $request->position ?? '';
        $input->city = $request->city ?? '';
        $input->salary = $request->salary ?? '';
        // Save the user in the database
        

        // Redirect to the form view with a success message
        return view('lead.list', ['filter' => $filter, 'input' => $input]);
    }
}
