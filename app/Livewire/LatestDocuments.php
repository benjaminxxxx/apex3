<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;

class LatestDocuments extends Component
{
    public $take = 5;
    public $widthImage = false;
    public $withShowMore = true;
    public function render()
    {
        $data['documents'] = Auth::user()->documents()->latest('created_at')->take($this->take)->get();
        return view('livewire.latest-documents', $data);
    }
}
