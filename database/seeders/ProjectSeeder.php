<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Friendship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Group;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $admins = User::where('role_id', 2)->get(); // Administradores
        $managers = User::where('role_id', 3)->get(); // Gestores
        $partners = User::where('role_id', 4)->get(); // Socios

        $projectCounter = 1;

        foreach ($admins as $admin) {
            for ($i = 1; $i <= 3; $i++) {
                // Crear proyecto
                
                $project = Project::create([
                    'name' => 'Project ' . $projectCounter,
                    'description' => 'Description for Project ' . $projectCounter,
                    'project_code' => Str::slug('Project ' . $projectCounter),
                    'administrator_id' => $admin->id,
                ]);

                // Asignar 2 gestores diferentes al proyecto
                $assignedManagers = $managers->random(2);
                foreach ($assignedManagers as $manager) {
                    // Registrar la relación entre proyecto y gestor
                    DB::table('manager_project')->insert([
                        'project_id' => $project->id,
                        'manager_id' => $manager->id,
                    ]);

                    // Crear amistad entre administrador y gestor si no existe
                    if (!Friendship::where(function ($query) use ($admin, $manager) {
                        $query->where('user_id', $admin->id)->where('friend_id', $manager->id);
                    })->orWhere(function ($query) use ($admin, $manager) {
                        $query->where('user_id', $manager->id)->where('friend_id', $admin->id);
                    })->exists()) {
                        Friendship::create([
                            'user_id' => $admin->id,
                            'friend_id' => $manager->id,
                            'status' => 'accepted',
                        ]);
                    }

                    // Asignar socios al gestor para este proyecto
                    //
                  
                    for ($j = 1; $j <= 2; $j++){
                       
                        $group = Group::create([
                            'name' => 'Group ' . $projectCounter . ' ' . $j,
                            'slug'=>Str::slug('Group ' . $projectCounter . ' ' . $j . ' ' . $project->id . ' ' . $i . ' ' . $manager->id),
                            'description' => 'Description for Group ' . $projectCounter . '-' . $j,
                            'manager_id' => $manager->id,
                            'project_id' => $project->id,
                        ]);

                        DB::table('group_project_manager')->insert([
                            'group_id' => $group->id,
                            'project_id' => $project->id,
                            'manager_id' => $manager->id,
                        ]);

                        $assignedPartners = $partners->random(4);

                        foreach ($assignedPartners as $partner) {
                            // Registrar la relación entre grupo y socio
                            DB::table('group_partner')->insert([
                                'group_id' => $group->id,
                                'partner_id' => $partner->id,
                            ]);

                            // Crear amistad entre gestor y socio si no existe
                            if (!Friendship::where(function ($query) use ($manager, $partner) {
                                $query->where('user_id', $manager->id)->where('friend_id', $partner->id);
                            })->orWhere(function ($query) use ($manager, $partner) {
                                $query->where('user_id', $partner->id)->where('friend_id', $manager->id);
                            })->exists()) {
                                Friendship::create([
                                    'user_id' => $manager->id,
                                    'friend_id' => $partner->id,
                                    'status' => 'accepted',
                                ]);
                            }

                            // Actualizar el campo created_by para el socio
                            $partner->update(['created_by' => $manager->id]);
                        }
                    }
                    
                }

                $projectCounter++;
            }
        }
    }
}