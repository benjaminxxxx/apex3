<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Group;

class GroupPanel extends Component
{
    public $group;
    public $name;
    public $description;
    public $group_id;
    public function mount(){
        $this->group = Group::find($this->group_id);
        $this->name =  $this->group->name;
        $this->description =  $this->group->description;
    }
    public function render()
    {
        return view('livewire.group-panel');
    }
    public function updateGroupData()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ],[
            'name.required' => 'El campo es obligatorio',
            'description.required' => 'El campo es obligatorio',
        ]);

        $this->group->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->dispatch('saved');
    }
}
