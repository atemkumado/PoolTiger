<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        // Get the list of countries from an array or a database table
        $provinces = ['USA', 'UK', 'Canada', 'Australia', 'India', 'China', 'Japan'];
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
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'province' => 'required',
        ]);

        // Create a new user instance
        $user = new User();
        // Assign the request data to the user attributes
        $user->name = $request->name;
        $user->email = $request->email;
        $user->country = $request->country;
        // Save the user in the database
        $user->save();

        // Redirect to the form view with a success message
        return redirect()->route('form')->with('success', 'User created successfully.');
    }
}
