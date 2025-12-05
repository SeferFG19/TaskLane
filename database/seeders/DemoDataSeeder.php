<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole      = Role::where('name', 'Admin')->first();
        $supervisorRole = Role::where('name', 'Supervisor')->first();
        $empleadoRole   = Role::where('name', 'Empleado')->first();

        // --- Usuarios ---
        $admin = User::create([
            'name'       => 'Super Admin',
            'email'      => 'admin@test.com',
            'password'   => Hash::make('password'),
            'is_admin'   => true,
            'created_by' => null,
        ]);

        $supervisor = User::create([
            'name'       => 'Supervisor Demo',
            'email'      => 'supervisor@test.com',
            'password'   => Hash::make('password'),
            'is_admin'   => false,
            'created_by' => $admin->id,
        ]);

        $empleado = User::create([
            'name'       => 'Empleado Demo',
            'email'      => 'empleado@test.com',
            'password'   => Hash::make('password'),
            'is_admin'   => false,
            'created_by' => $admin->id,
        ]);

        // usuario => rol en el proyecto
        $usuarios = [
            [$admin,      $adminRole],
            [$supervisor, $supervisorRole],
            [$empleado,   $empleadoRole],
        ];

        foreach ($usuarios as [$user, $role]) {

            for ($i = 1; $i <= 3; $i++) {

                // --- Proyecto ---
                $project = Project::create([
                    'name'        => "Proyecto {$user->name} {$i}",
                    'description' => "Proyecto de pruebas {$i} de {$user->name}.",
                    'created_by'  => $user->id,
                ]);

                // pivot project_user
                $project->users()->attach($user->id, [
                    'role_id' => $role->id,
                ]);

                // Añadimos siempre al Empleado Demo como empleado en este proyecto
                if ($user->id !== $empleado->id) {
                    $project->users()->attach($empleado->id, [
                        'role_id' => $empleadoRole->id,
                    ]);
                }


                // --- Tablero principal ---
                $board = $project->boards()->create([
                    'name'       => 'Tablero principal',
                    'created_by' => $user->id,
                ]);

                // --- Listas / columnas del tablero ---
                $board->tlists()->createMany([
                    ['name' => 'Importante',  'color' => '#e74c3c'],
                    ['name' => 'En curso',    'color' => '#3498db'],
                    ['name' => 'Pendiente',   'color' => '#f1c40f'],
                    ['name' => 'Revisión',    'color' => '#9b59b6'],
                    ['name' => 'Completado',  'color' => '#2ecc71'],
                ]);

                // --- Tags disponibles en este tablero ---
                $board->tags()->createMany([
                    ['name' => 'Hot Fix',   'color' => '#e74c3c'],
                    ['name' => 'FrontEnd',  'color' => '#3498db'],
                    ['name' => 'BackEnd',   'color' => '#ff7e00'],
                    ['name' => 'Debug',       'color' => '#88d621ff'],
                    ['name' => 'Excelencia',    'color' => '#8e44ad'],
                ]);

                // (Opcional) Crear 1 tarjeta de ejemplo en la primera lista
                $firstList = $board->tlists()->first();
                if ($firstList) {
                    $firstList->cards()->create([
                        'title'       => "Tarea demo {$i}",
                        'description' => "Descripción de la tarea demo {$i} para {$user->name}.",
                        'assigned_to' => null,   // o $empleado->id si quieres asignarla
                    ]);
                }
            }
        }
    }
}
