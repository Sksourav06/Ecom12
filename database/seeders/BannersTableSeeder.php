<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bannersRecords = [
            [
                'id' => 1,
                'type' => 'Slider',
                'image' => 'carousel-1.jpg',
                'link' => '',
                'title' => 'Product on Sale',
                'alt' => 'Product on Sale',
                'sort' => 1,
                'status' => 1
            ],
            [
                'id' => 2,
                'type' => 'Slider',
                'image' => 'carousel-2.jpg',
                'link' => '',
                'title' => 'Flat 50% off',
                'alt' => 'Flat 50% off',
                'sort' => 2,
                'status' => 1
            ],
            [
                'id' => 3,
                'type' => 'Slider',
                'image' => 'carousel-3.jpg',
                'link' => '',
                'title' => 'Summer Sale',
                'alt' => 'Summer Sale',
                'sort' => 3,
                'status' => 1
            ]
        ];
        foreach ($bannersRecords as $record) {
            Banner::create($record);
        }
    }
}
