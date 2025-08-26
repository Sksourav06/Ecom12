<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories =[
            ['parent_id'=>null,'name'=>'Clothing','url'=>'clothing'],
            ['parent_id'=>null,'name'=>'electronice','url'=>'electronice'],
            ['parent_id'=>null,'name'=>'appliances','url'=>'appliances'],
            ['parent_id'=>1,'name'=>'Men','url'=>'men'],
            ['parent_id'=>1,'name'=>'Women','url'=>'women'],
            ['parent_id'=>1,'name'=>'Kids','url'=>'kids'],
            ['parent_id'=>4,'name'=>'Men T-Shirts','url'=>'men-t-shirts'],
        ];
        foreach ($categories as $data){
            Category::create([
                'parent_id'=>$data['parent_id'],
                'name'=>$data['name'],
                'url'=>$data['url'],
                'image'=>"",
                'size_chart'=>'',
                'discount'=>0,
                'description'=>'',
                'meta_title'=>'',
                'meta_description'=>'',
                'meta_keywords'=>'',
                'status'=>1,
                'menu_status'=>1,
                'created_at'=>Carbon::now(),
                'updated_at' =>Carbon::now(),

            ]);
        }
    }
}
