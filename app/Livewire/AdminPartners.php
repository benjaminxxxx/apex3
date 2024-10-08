<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Friendship;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Validation\Rule;

class AdminPartners extends Component
{
    public $partners;
    public $roles;
    public $isFormOpen;
    public $projects;
    public $assignedProjects = [];
    public $assignedGroups = [];
    public $isEditing;
    public $user_code;
    public $userCodeToDelete;

    public $userId;
    public $nickname;
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $role_id;
    public $birthdate;
    public $phone;
    public $address;

    protected $listeners = ['userModified'=>'$refresh'];
    public function mount()
    {
        $this->roles = Role::where(['id' => '4'])->get();
        if ($this->roles) {
            $this->role_id = $this->roles->first()->id;
        }
        $this->projects = Auth::user()->managedProjects;
    }
    public function render()
    {
        $this->partners = User::where('created_by', Auth::id())->with(['assignedProjects' => function ($query) {
            $query->where('manager_id', Auth::id());
        }])->get();

        return view('livewire.admin-partners');
    }
    public function edit($userCode)
    {
        $user = User::where('user_code', $userCode)->with(['assignedProjects' => function ($query) {
            $query->where('manager_id', Auth::id());
            $query->with('project'); 
        }])->first();

        if ($user) {
            $this->userId = $user->id;
            $this->user_code = $user->user_code;
            $this->nickname = $user->nickname;
            $this->name = $user->name;
            $this->lastname = $user->lastname;
            $this->email = $user->email;
            $this->role_id = $user->role_id;
            $this->birthdate = $user->birthdate;
            $this->phone = $user->phone;
            $this->address = $user->address;
            $this->isEditing = true;
            $this->isFormOpen = true;

            $this->assignedProjects = $user->assignedProjects->pluck('project_id')->toArray();
            $this->assignedGroups = $user->groupPartners->pluck('id')->toArray();
        
        }

    }
    public function save()
    {
        $main_rules = [
            'nickname' => [
                'required',
                'regex:/^[a-zA-Z0-9]+$/',
                $this->isEditing ? Rule::unique('users', 'nickname')->ignore($this->user_code, 'user_code') : 'unique:users,nickname'
            ],
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                $this->isEditing ? Rule::unique('users', 'email')->ignore($this->user_code, 'user_code') : 'unique:users,email'
            ],
            'password' => $this->isEditing ? 'nullable|string|min:8' : 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ];

        if ($this->birthdate) {
            $main_rules['birthdate'] = 'date';
        }

        $this->validate($main_rules, [
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
            'birthdate.date' => 'La fecha de nacimiento debe ser una fecha válida.',
        ]);

        try {

            $data_user = [
                'nickname' => $this->nickname,
                'name' => $this->name,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'phone' => $this->phone,
                'address' => $this->address,
                'birthdate' => $this->birthdate ?? null, // Validar la fecha si no es nula
                'created_by'=>Auth::id()
            ];

            if (!$this->isEditing) {

                $data_user['user_code'] = bin2hex(random_bytes(10));
                $data_user['password'] = Hash::make($this->password);
                $user = User::create($data_user);

                Friendship::create([
                    'user_id' => Auth::id(),
                    'friend_id' => $user->id,
                    'status' => 'accepted',
                ]);
                session()->flash('message', 'Usuario creado con éxito.');

            } else {

                $user = User::where('user_code', $this->user_code)->firstOrFail();
                if (!empty($this->password)) {
                    $data_user['password'] = Hash::make($this->password);
                }
                $user->update($data_user);
                session()->flash('message', 'Usuario actualizado con éxito.');

            }
            $manager = Auth::user();

            DB::table('group_partner')->where('partner_id', $user->id)->whereIn('group_id', function($query) use ($manager) {
                $query->select('id')
                      ->from('groups')
                      ->where('manager_id', $manager->id);
            })->delete();

            if (!empty($this->assignedGroups)) {
                foreach ($this->assignedGroups as $groupId) {
                    DB::table('group_partner')->insert([
                        'group_id' => $groupId,
                        'partner_id' => $user->id,
                    ]);
                }
            }
/*
            DB::table('project_manager_partner')->where('partner_id', $user->id)->where('manager_id', Auth::id())->delete();

            if (!empty($this->assignedGroups)) {
                foreach ($this->assignedGroups as $groupId) {
                    DB::table('group_partner')->insert([
                        'group_id' => $groupId,
                        'partner_id' => $user->id,
                    ]);
                }
            }*/

            $this->closeForm();
        } catch (QueryException $e) {
            session()->flash('error', 'Hubo un error al crear el usuario: ' . $e->getMessage());
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
        $this->user_code = null;
        $this->nickname = null;
        $this->name = null;
        $this->lastname = null;
        $this->email = null;
        $this->birthdate = null;
        $this->phone = null;
        $this->address = null;
    }
}
