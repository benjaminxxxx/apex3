<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Auth;

class GroupController extends Controller
{
    public function go($slug)
    {

        $group = Group::where('slug', $slug)->first();

        if (!$group)
            return back();

        if (!Auth::user()->belongsToGroup($group->id)) {
            return view('group.denied');
        }

        $project_id = $group->project_id;
        
        return view('group.panel', [
            'group_id' => $group->id,
            'project_id' => $project_id
        ]);
    }
}
