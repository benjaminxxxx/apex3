<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(){
        return view('project.index');
    }
    public function go($slug=null){

        $project = Project::where('project_code', $slug)->first();

        if (!$project) {
            return redirect()->route('projects');
        }

        return view('project.panel',['project_id'=>$project->id]);
    }
}
