<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'role_name' => 'admin',
        ]);

        Role::create(['role_name' => 'asesor']);
        Role::create(['role_name' => 'socio']);
        Role::create(['role_name' => 'colaborador']);

        // Crear un usuario con role_id = 1 (admin)
        User::create([
            'name' => 'Benjamin',
            'email' => 'benjamin_unitek@hotmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $adminRole->id,
        ]);
    }
}
