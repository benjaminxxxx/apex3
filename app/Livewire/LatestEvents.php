<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Auth;

class LatestEvents extends Component
{
    public $take = 5;
    public $widthImage = false;
    public $withShowMore = true;
    public function render()
    {

        $data['events'] = Auth::user()->events()->latest('created_at')->take($this->take)->get();

        return view('livewire.latest-events', $data);
    }
}
