<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Tag;

class TagsNews extends Component
{
    public function render()
    {
        $newsCategory = Category::where('slug', 'noticias')->first();
        $data = [];

        // Obtener etiquetas relacionadas con las noticias
        if ($newsCategory) {
            $data['tags'] = Tag::whereHas('posts.categories', function ($query) use ($newsCategory) {
                $query->where('categories.id', $newsCategory->id);
            })->get();
        }

        return view('livewire.tags-news',$data );
    }
}
