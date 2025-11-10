<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Board;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            "Crítico"   => "#e74c3c",
            "FrontEnd"  => "#3498db",
            "BackEnd"   => "#ff7e00",
        ];

        foreach ($tags as $name => $color) {
            Tag::create([
                'name'      => $name,
                'color'     => $color,
                'board_id'  => Board::inRandomOrder()->first()->id,
            ]);
        }
    }
}
