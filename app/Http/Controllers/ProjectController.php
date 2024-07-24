<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Group;
use Auth;

class ProjectController extends Controller
{
    public function index(){
        return view('project.index');
    }
    public function go($slug=null){

        $project = Project::where('project_code', $slug)->first();

        if (!$project) 
            return redirect()->route('projects');
  
        if(!Auth::user()->hasPermissionToManage($project->id))
            return view('project.denied');
/*
        if(Auth::user()->role_id==4){
            $groups = Group::where('project_id', $project->id)
                        ->whereHas('partners', function ($query) {
                            $query->where('partner_id', Auth::id());
                        })->get();

            return view('project.select_group',['groups'=>$groups]);
        }*/
        
        return view('project.panel',['project_id'=>$project->id]);
    }
}
