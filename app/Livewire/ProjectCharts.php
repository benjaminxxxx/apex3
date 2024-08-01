<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectCharts extends Component
{
    public $project_id;
    public $charts;
    public function render()
    {
        $this->charts = Project::find($this->project_id)
            ->charts()
            ->latest('created_at')
            ->get();
     
        return view('livewire.project-charts');
    }
}
