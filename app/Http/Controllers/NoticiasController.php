<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Post;

class NoticiasController extends Controller
{
    public function index()
    {

        $data = [
            'categories' => null,
            'posts' => null
        ];

        // Obtener la categoría de noticias
        $newsCategory = Category::where('slug', 'noticias')->first();


        // Obtener subcategorías de noticias con el conteo de posts
        if ($newsCategory) {
            $data['categories'] = Category::where('parent_id', $newsCategory->id)
                ->withCount('posts')
                ->get();
        } else {
            $data['categories'] = collect();
        }

        // Obtener las primeras 5 noticias relacionadas con la categoría de noticias y sus subcategorías
        if ($newsCategory) {
            $subCategoryIds = $newsCategory->children()->pluck('id')->toArray();
            $categoryIds = array_merge([$newsCategory->id], $subCategoryIds);
            $data['posts'] = Post::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->latest()
                ->take(5)
                ->get();
        } else {
            $data['posts'] = collect();
        }

        return view('admin.noticias', $data);
    }
    public function loadMoreNotices(Request $request)
    {
        $offset = $request->input('offset', 0);

        $newsCategory = Category::where('slug', 'noticias')->first();

        if ($newsCategory) {
            $subCategoryIds = $newsCategory->children()->pluck('id')->toArray();
            $categoryIds = array_merge([$newsCategory->id], $subCategoryIds);
            $posts = Post::with('categories')
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->latest()
                ->skip($offset)
                ->take(5)
                ->get();
        } else {
            $posts = collect();
        }

        return response()->json($posts);
    }
}
