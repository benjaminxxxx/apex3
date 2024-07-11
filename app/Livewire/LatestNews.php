<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class LatestNews extends Component
{
    public $take = 5;
    public $widthImage = false;
    public $withShowMore = true;
    public function render()
    {
        $data['noticias'] = Post::withCount('comments')
            ->where('type', 'noticia')
            ->latest('created_at') 
            ->take($this->take)
            ->get();

        return view('livewire.latest-news', $data);
    }
}
