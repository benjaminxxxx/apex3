<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Auth;

class PostController extends Controller
{
    public function index($type = null)
    {
        if(!Auth::user()->hasPermission('add_news')){
            return redirect()->route('news');
        }
        $types = ['noticia', 'evento', 'publicacion', 'foro'];

        if ($type && !in_array($type, $types)) {
            return redirect()->route('post.new', ['type' => 'noticia']);
        }

        return view('admin.post', ['type' => $type]);
    }
    
    
    
}
