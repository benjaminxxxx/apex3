<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class LastPost extends Component
{
    public function render()
    {
        $data['post'] = Post::withCount('comments')->latest('created_at')
            ->first();
            
        return view('livewire.last-post',$data);
    }
}
