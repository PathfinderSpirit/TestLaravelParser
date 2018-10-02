<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    function index()
    {
        $articles = array();
        return view("main", array('articles' =>array('<h1>Article 1</h1>', '<h1>Article 2</h1>', '<h1>Article 3</h1>')));
    }
}
