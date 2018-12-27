<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class MainController extends Controller
{
    function index()
    {
        $posts = Post::paginate(10);
        return view('main', ['posts' => $posts]);
    }
}
