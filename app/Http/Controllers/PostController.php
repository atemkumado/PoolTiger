<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return "Hello World";
    }

    public function save(Request $request)
    {
        $post = new Post();
        echo json_encode($request);
    }
}
