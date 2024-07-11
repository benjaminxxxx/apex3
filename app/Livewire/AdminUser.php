<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use App\Models\Group;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Auth;

class AdminUser extends Component
{

    public $projects;
    public $activate_grouping;
    public function mount()
    {
        $this->activate_grouping = false;
    }

    public function render()
    {
        
        switch (Auth::user()->role_id) {
            case '1':
                
                $this->projects = Project::all();
                break;
            case '2':
                $groups = Group::with(['users'])->where('administrator_id', Auth::id())->get();
                $uniqueUsers = collect();
                foreach ($groups as $group) {
                    $users = $group->users;
                    $uniqueUsers = $uniqueUsers->merge($users);
                }
                $uniqueUsers = $uniqueUsers->unique('id');


                $this->projects = Group::where('administrator_id', Auth::id())->get();
                break;

            default:
                # code...
                break;
        }

        return view('livewire.admin-user');
    }
    public function activating_grouping()
    {
        $this->render();
    }
}
