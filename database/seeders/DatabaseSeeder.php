<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Card;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            
        ]);

        User::factory(5)->create();
        Project::factory(3)->create();
        Board::factory(3)->create();
        

        $this->call([
            TagSeeder::class,
            TlistSeeder::class,
        ]);

        Card::factory(5)->create();
        ProjectUser::factory(3)->create();
    }
}
