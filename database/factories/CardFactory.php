<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Tlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tlists = Tlist::all();
        return [
            'title' => fake()->unique()->realText(10),
            'description' => fake()->realText(150),
            'tlist_id' => $tlists->random()->id
        ];
    }
}
