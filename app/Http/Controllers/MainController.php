<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parser;

class MainController extends Controller
{
    function index()
    {
        $parser = new Parser();
        $articles = $parser->getMainPageContent("https://habr.com/all/");
        return view("main")->with(array('articles'=>$articles));
    }
}
