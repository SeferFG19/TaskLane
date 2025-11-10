<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Tlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tlist = [
            "Importante" => "#e74c3c",
            "En curso" => "#3498db",
            "Pendiente" => "#ff7e00",
            "Revisión" => "#f1c40f",
            "Completado" => "#2ecc71"
        ];
        foreach ($tlist as $name => $color) {
            Tlist::create([
                'name'     => $name,
                'color'    => $color,
                'board_id' => Board::inRandomOrder()->first()->id,
            ]);
        }
    }
}