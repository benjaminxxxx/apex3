<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Database\QueryException;

class GroupMembers extends Component
{
    public $user_search = '';
    public $users;
    public $project_id;
    public $members;
    public $roles;
    public $isFormOpen;

    public $nickname;
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $role_id;
    public function mount($project_id)
    {
        
        if (Auth::user()->role_id == '2') {
            
            $this->roles = Role::where(['id'=>'3'])->get();
            if ($this->roles) {
                $this->role_id = '3';
            }
        }
        
    }
    public function render()
    {
        $project = Project::find($this->project_id);
        $this->members = $project->managers;
        return view('livewire.group-members');
    }
    public function search()
    {
        if ($this->user_search) {
            $this->users = User::where('name', 'like', '%' . $this->user_search . '%')
                ->orWhere('email', 'like', '%' . $this->user_search . '%')
                ->get();
        } else {
            $this->users = null;
        }

    }
    public function addMember($userId)
    {

        $project = Project::find($this->project_id);

        if ($project->users()->where('user_id', $userId)->exists()) {
            session()->flash('error', 'El usuario ya está en el grupo.');
            return;
        }

        $project->users()->attach($userId);

        session()->flash('message', 'Usuario agregado al grupo exitosamente.');
        $this->user_search = '';
        $this->users = null;
    }
    public function destroyFromGroup($userId)
    {
        $user = User::findOrFail($userId);
        $user->groups()->detach($this->project_id);
        session()->flash('message', 'Usuario desagregado al grupo exitosamente.');
    }
    public function save()
    {
        $this->validate([
            'nickname' => ['required', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,nickname'],
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ], [
            'nickname.required' => 'El usuario es obligatorio.',
            'nickname.regex' => 'El usuario solo puede contener letras y números.',
            'nickname.unique' => 'El usuario ya está en uso.',
            'name.required' => 'El nombre es obligatorio.',
            'lastname.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'role_id.required' => 'El rol es obligatorio.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ]);

        try {
            $user = User::create([
                'nickname' => $this->nickname,
                'name' => $this->name,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
            ]);

            $this->addMember($user->id);

            session()->flash('message', 'Usuario creado con éxito.');

            $this->closeForm();
        } catch (QueryException $e) {
            session()->flash('error', 'Hubo un error al crear el usuario: ' . $e->getMessage());
        }

        $this->closeForm();
    }
    public function openForm()
    {
        $this->isFormOpen = true;
    }
    public function closeForm()
    {
        $this->isFormOpen = false;
    }
}
