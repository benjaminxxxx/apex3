<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Post;
use Auth;

class NoticiasController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $data = [
            'categories' => $user->getCategories(),
            'posts' => $user->getMyNews()
        ];

        return view('admin.noticias', $data);
    }
    public function loadMoreNotices(Request $request)
    {
        $offset = $request->input('offset', 0);
        $user = Auth::user();

        $posts = $user->getMyNews($offset);

        return response()->json($posts);
    }
}
