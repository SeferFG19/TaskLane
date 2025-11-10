<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
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
        return [
            'name' => fake()->unique()->realText(10),
            'project_id' => $projects->random()->id,
            'created_by' => $users->random()->id
        ];
    }
}
