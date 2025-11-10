<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectUser>
 */
class ProjectUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projects = Project::all();
        $users = User::all();
        $roles = Role::all();
        return [
            'project_id' => $projects->random()->id,
            'user_id' => $users->random()->id,
            'role_id' => $roles->random()->id
        ];
    }
}
