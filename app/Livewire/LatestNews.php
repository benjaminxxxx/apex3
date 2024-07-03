<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class LatestNews extends Component
{
    public function render()
    {
        $data['noticias'] = Post::where('type', 'noticia') 
            ->latest('created_at') 
            ->take(5)
            ->get();

        return view('livewire.latest-news', $data);
    }
}
