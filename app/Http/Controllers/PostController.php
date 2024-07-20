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
    public function noticia($slug = null)
    {

        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return redirect()->route('news');
        }

        $allowed_roles_id = $post->visibilityLevels()->pluck('visibility_level')->toArray();
        if(!in_array(Auth::user()->role_id,$allowed_roles_id) && Auth::user()->role_id!=1){
            return view('post.denied');
        }

        return view('post.new', ['post' => $post]);
    }
    public function evento($slug = null)
    {

        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return redirect()->route('eventos');
        }

        return view('post.event', ['post' => $post]);
    }
    public function eventos()
    {

        $posts = Post::where('type', 'evento')->get();
        return view('eventos', ['posts' => $posts]);
    }
}
