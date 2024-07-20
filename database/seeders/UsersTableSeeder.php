<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::create(['name' => 'Super Administrator']);
        $adminRole = Role::create(['name' => 'Administrator']);
        $managerRole = Role::create(['name' => 'Manager']);
        $partnerRole = Role::create(['name' => 'Partner']);
        $collaboratorRole = Role::create(['name' => 'Collaborator']);

        $addNews = Permission::create(['name' => 'add_news']);
        $editNews = Permission::create(['name' => 'edit_news']);
        $deleteNews = Permission::create(['name' => 'delete_news']);
        $addProjects = Permission::create(['name' => 'add_projects']);
        $addGroup = Permission::create(['name' => 'add_group']);

        $superAdminRole->permissions()->attach([$addNews->id, $editNews->id, $deleteNews->id,$addProjects->id]);
        $adminRole->permissions()->attach([$addNews->id, $editNews->id, $deleteNews->id,$addProjects->id]);
        $managerRole->permissions()->attach([$addNews->id, $editNews->id, $deleteNews->id,$addGroup->id]);

        // Crear un super administrador
        User::create([
            'name' => 'Benjamin',
            'email' => 'benjamin_unitek@hotmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $superAdminRole->id,
            'birthdate' => '1980-01-01',
            'phone' => '123456789',
            'address' => '123 Main Street',
            'user_code' => Str::random(20),
            'nickname' => '12345678', // DNI ficticio de 8 dígitos
        ]);

        // Crear dos administradores
        User::create([
            'name' => 'Admin1',
            'email' => 'admin1@hotmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $adminRole->id,
            'birthdate' => '1985-02-01',
            'phone' => '987654321',
            'address' => '456 Elm Street',
            'user_code' => Str::random(20),
            'nickname' => '87654321', // DNI ficticio de 8 dígitos
        ]);

        User::create([
            'name' => 'Admin2',
            'email' => 'admin2@hotmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $adminRole->id,
            'birthdate' => '1990-03-01',
            'phone' => '234567890',
            'address' => '789 Oak Street',
            'user_code' => Str::random(20),
            'nickname' => '23456789', // DNI ficticio de 8 dígitos
        ]);

        // Crear cuatro gestores
        for ($i = 1; $i <= 4; $i++) {
            User::create([
                'name' => 'Manager' . $i,
                'email' => 'manager' . $i . '@hotmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => $managerRole->id,
                'birthdate' => '1990-0' . $i . '-01',
                'phone' => '12345678' . $i,
                'address' => '456 Elm Street Apt ' . $i,
                'user_code' => Str::random(20),
                'nickname' => '3456789' . $i, // DNI ficticio de 8 dígitos
            ]);
        }

        // Crear diez socios
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Socio' . $i,
                'email' => 'socio' . $i . '@hotmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => $partnerRole->id,
                'birthdate' => '2000-0' . ($i % 9 + 1) . '-01',
                'phone' => '3456789' . $i,
                'address' => '789 Oak Street Apt ' . $i,
                'user_code' => Str::random(20),
                'nickname' => '4567890' . $i, // DNI ficticio de 8 dígitos
            ]);
        }
    }
}
