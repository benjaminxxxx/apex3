<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;
class LatestCharts extends Component
{
    public $charts;
    public function mount(){
        $this->charts = Auth::user()->getChartPublishes()->latest('created_at')->take(5)->get();
    }
    public function render()
    {
        return view('livewire.latest-charts');
    }
}
