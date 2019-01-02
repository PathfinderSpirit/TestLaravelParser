<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class MainController extends Controller
{
    function index($sort='desc')
    {
        $posts = Post::orderBy('created_at', $sort)->paginate(10);
        return view('main', ['posts' => $posts]);
    }
}
