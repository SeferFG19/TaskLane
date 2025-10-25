<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            "Importante" => "#e74c3c",
            "En curso" => "#3498db",
            "Pendiente" => "#ff7e00",
            "Revisión" => "#f1c40f",
            "Completado" => "#2ecc71"
        ];
        foreach($tags as $name=>$color){
            Tag::create([compact('name', 'color')]);
        }
    }
}
