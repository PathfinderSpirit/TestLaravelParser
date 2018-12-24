<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{

    public function index()
    {
        
    }
    public function create_post($data)
    {
        $post = new Post;

        $post->text=$data->text;
        $post->articleId=$data->articleId;
        $post->created_at=$data->created_at;
        $post->save();
    }
    public function get_articleId_list()
    {
        
    }

}
