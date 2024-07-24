<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Auth;

class NewController extends Controller
{
    public function index()
    {
        return view('new.home');
    }
    public function show($slug = null)
    {

        $article = Post::where('slug', $slug)->first();

        if (!$article)
            return redirect()->route('news');
        
        if(!Auth::user()->isAllowedToViewArticle($article->id)){
            return view('new.denied');
        }

        return view('new.show', ['article' => $article]);
    }
   
}
