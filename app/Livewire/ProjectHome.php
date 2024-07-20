<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\Project;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class ProjectHome extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function store()
    {
        try {

            $this->validate();

            $slug = Str::slug($this->name);
            $count = Project::where('project_code', $slug)->count();

            if ($count > 0) {
                $suffix = 1;
                do {
                    $newSlug = $slug . '-' . $suffix;
                    $count = Project::where('project_code', $newSlug)->count();
                    $suffix++;
                } while ($count > 0);
                $groupCode = $newSlug;
            } else {
                $groupCode = $slug;
            }

            Project::create([
                'name' => $this->name,
                'project_code' => $groupCode,
                'administrator_id' => Auth::id()
            ]);
            $this->reset(['name']);
            session()->flash('message', __('Project created successfully!'));

        } catch (QueryException $e) {

            $errorCode = $e->errorInfo[1];
            $this->addError('error_message', 'Hubo un problema al crear el grupo (' . $errorCode . '): ' . $e->getMessage());
        }
    }
    public function render()
    {
        $projects = collect();

        switch (Auth::user()->role_id) {

            case '3':
                $projects = Auth::user()->managedProjects;
                break;

            case '4':
                $projects = Auth::user()->projectsAsPartner();
                break;

            default:
                $projects = Project::all();
                break;
        }
        return view('livewire.project-home', ['projects' => $projects]);
    }
    public function destroy($projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $project->status = 2;
            $project->save();
            session()->flash('message', 'Proyecto eliminado');

        } catch (QueryException $e) {

            $errorCode = $e->errorInfo[1];
            $this->addError('error_message', 'Hubo un problema al eliminar el grupo (' . $errorCode . '): ' . $e->getMessage());
        }
    }
}
