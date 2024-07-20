<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Group;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class Groups extends Component
{
    public $groups;
    public $projectId;
    public $name;
    public function mount(){
         
    } 
    public function render()
    {
        //$this->groups = Project::find($this->projectId)->groups()->get();
        $this->groups = Auth::user()->myGroups($this->projectId);
        return view('livewire.groups');
    }

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function store()
    {
        try {

            $this->validate();

            $slug = Str::slug($this->name);
            $count = Group::where('slug', $slug)->count();

            if ($count > 0) {
                $suffix = 1;
                do {
                    $newSlug = $slug . '-' . $suffix;
                    $count = Group::where('slug', $newSlug)->count();
                    $suffix++;
                } while ($count > 0);
                $groupCode = $newSlug;
            } else {
                $groupCode = $slug;
            }

            Group::create([
                'name' => $this->name,
                'slug' => $groupCode,
                'manager_id' => Auth::id(),
                'project_id'=>$this->projectId
            ]);
            $this->reset(['name']);
            session()->flash('message', __('Group created successfully!'));

        } catch (QueryException $e) {

            $errorCode = $e->errorInfo[1];
            $this->addError('error_message', 'Hubo un problema al crear el grupo (' . $errorCode . '): ' . $e->getMessage());
        }
    }
}
