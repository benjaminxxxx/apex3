<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectPanel extends Component
{
    use WithFileUploads;
    public $project_id;
    public $cover_image;
    public $profile_image;
    public $name;
    public $description;
    public $project;
    public function mount($project_id){
        $this->project_id = $project_id;
        $this->project = Project::find($this->project_id);
        $this->name =  $this->project->name;
        $this->description =  $this->project->description;
    }
    
    
    public function updatedCoverImage()
    {
        
        $this->validate([
            'cover_image' => 'mimes:jpg,jpeg,png|max:1024',
        ], [
            'cover_image.image' => 'El archivo debe ser una imagen.',
            'cover_image.max' => 'La imagen no debe ser mayor de 1MB.',
        ]);
        $imageName = strtolower(Str::random(20)) . '.' . $this->cover_image->getClientOriginalExtension();

        $currentYear = date('Y');
        $currentMonth = date('m');

        // Guardar la imagen en la ruta especificada
        $this->cover_image->storeAs(
            'public/cover/' . $currentYear . '/' . $currentMonth,
            $imageName
        );
        $imageCoverPath = 'cover/' . $currentYear . '/' . $currentMonth . '/' . $imageName;
        $group = $this->project;
        $group->cover_image = $imageCoverPath;
        $group->save();
    }
    public function updatedProfileImage()
    {
        
        $this->validate([
            'profile_image' => 'mimes:jpg,jpeg,png|max:1024',
        ], [
            'profile_image.image' => 'El archivo debe ser una imagen.',
            'profile_image.max' => 'La imagen no debe ser mayor de 1MB.',
        ]);

        if($this->project->profile_image){
            Storage::delete('public/' . $this->project->profile_image);
        }
        $imageName = strtolower(Str::random(20)) . '.' . $this->profile_image->getClientOriginalExtension();

        $currentYear = date('Y');
        $currentMonth = date('m');

        // Guardar la imagen en la ruta especificada
        $this->profile_image->storeAs(
            'public/profile/' . $currentYear . '/' . $currentMonth,
            $imageName
        );
        $imageCoverPath = 'profile/' . $currentYear . '/' . $currentMonth . '/' . $imageName;
        $group = $this->project;
        $group->profile_image = $imageCoverPath;
        $group->save();
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

        $this->project->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->dispatch('saved'); // Emitir evento para mostrar mensaje de éxito

        // Puedes limpiar las variables después de la actualización si es necesario
        // $this->reset(['name', 'description']);
    }

    public function render()
    {
        return view('livewire.project-panel');
    }
}
