<?php

namespace App\Livewire;

use App\Models\Friendship;
use Illuminate\Database\QueryException;
use Livewire\Component;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Auth;

class UserList extends Component
{
    use LivewireAlert;
    public $userId;
    public $nickname;
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $role_id;
    public $roles;
    public $users;
    public $isFormOpen = false;
    public $isEditing = false;
    public $message;
    public $userIdToDelete;
    public $isDeleting = false;
    public $projectId;
    public $projects;
    protected $listeners = ['userModified'=>'$refresh'];
    protected function rules()
    {
        return [
            'nickname' => ['required', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,nickname,' . $this->userId],
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email,' . $this->userId],
            'password' => $this->isEditing ? 'nullable|string|min:8' : 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ];
    }
    protected $messages = [
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
    ];
    public function render()
    {
        
        
        if ($this->role_id == null) {
            $this->role_id = User::first()->id;
        }
        //Listar amistades
        $user = Auth::user();

        switch (Auth::user()->role_id) {
            case '1':
                $this->users = User::all();
                $this->roles = Role::all();
                $this->projects = Group::all();
                break;
            case '2':
                $groups = Group::with(['users'])->where('administrator_id', Auth::id())->get();
                $uniqueUsers = collect();
                foreach ($groups as $group) {
                    $users = $group->users;
                    $uniqueUsers = $uniqueUsers->merge($users);
                }
                $uniqueUsers = $uniqueUsers->unique('id');
                $this->users = $uniqueUsers;

                $this->roles = Role::where('id','3')->get();
                $this->projects = $groups;
                break;

            default:
                # code...
                break;
        }
        return view('livewire.user-list');
    }
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->nickname = $user->nickname;
        $this->name = $user->name;
        $this->lastname = $user->lastname;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->isEditing = true;
        $this->isFormOpen = true;
    }
    public function save()
    {

        $this->validate();

        if ($this->isEditing) {
            // Actualizar usuario existente
            $user = User::findOrFail($this->userId);
            $user->nickname = $this->nickname;
            $user->name = $this->name;
            $user->lastname = $this->lastname;
            $user->email = $this->email;
            $user->role_id = $this->role_id;

            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }

            $user->save();
            session()->flash('message', 'Usuario actualizado con éxito.');
        } else {
            // Crear nuevo usuario
            User::create([
                'nickname' => $this->nickname,
                'name' => $this->name,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
            ]);
            ;
            session()->flash('message', 'Usuario creado con éxito.');
        }
        $this->closeForm();
    }
    
   
    public function openForm()
    {
        $this->resetForm();
        $this->isFormOpen = true;
        $this->isEditing = false;
    }
    public function closeForm()
    {
        $this->resetForm();
        $this->isFormOpen = false;
        $this->isEditing = false;
    }
    public function resetForm()
    {
        $this->userId = null;
        $this->nickname = '';
        $this->name = '';
        $this->lastname = '';
        $this->email = '';
    }
}
