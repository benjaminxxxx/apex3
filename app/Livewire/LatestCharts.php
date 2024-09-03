<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chartpublish;
use Auth;
class LatestCharts extends Component
{
    public $charts;
    public $isFormOpen = false;
    public $title;
    public $description;
    public $selectedChartId;
    public function mount(){
        
    }
    public function render()
    {
        $this->charts = Auth::user()->getChartPublishes()->latest('created_at')->take(5)->get();
        return view('livewire.latest-charts');
    }
    public function delete($code){

        $chart = Chartpublish::where('code', $code)->first();
        if($chart){
            $chart->delete();
        }
    }
    public function store(){
        if($this->selectedChartId){
            $chart = Chartpublish::find($this->selectedChartId);
            if($chart){
                $chart->title = $this->title;
                $chart->description = $this->description;
                $chart->save();
                $this->cancel();
            }
        }
    }
    public function edit($code){

        $chart = Chartpublish::where('code', $code)->first();
       
        if($chart){
            $this->selectedChartId = $chart->id;
            $this->title = $chart->title;
            $this->description = $chart->description;
            $this->isFormOpen = true;
        }
    }
    public function cancel(){
        $this->selectedChartId = null;
        $this->title = null;
        $this->description = null;
        $this->isFormOpen = false;
    }
}
