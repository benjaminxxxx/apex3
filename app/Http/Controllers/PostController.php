<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index($type = null)
    {

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
            return redirect()->route('notices');
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
