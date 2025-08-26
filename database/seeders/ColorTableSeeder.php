<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['Back','Blue','Brown','Green','Grey','Multi','Olive','Orange','Pink','Purple','Red','White','Yellow'];
        foreach($colors as $colorName){
            $color = new Color;
            $color->name=$colorName;
            $color->status =1;
            $color->save();
        }
    }
}
