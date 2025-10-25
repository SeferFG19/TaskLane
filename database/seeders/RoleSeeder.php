<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "Admin" => "Puede crear, editar y eliminar cualquier tarea del proyecto y gestinar permisos y roles.",
            "Empleado" => "Puede crear y editar tarjetas de un proyecto.",
            "Observador" => "Puede visualizar las diferentes tareas pero no puede gestionar nada."
        ];
        foreach($roles as $name=>$description){
            Role::create([compact('name', 'description')]);
        }
    }
}
