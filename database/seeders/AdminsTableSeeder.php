<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make("123456");
        $admin = new Admin;
        $admin->name = "Sourav";
        $admin->role ="admin";
        $admin->mobile = "98000000000";
        $admin->email = "admin@admin.com";
        $admin->password = $password;
        $admin->status =1;
        $admin->save();

        // $admin =new Admin();
        // $admin->name = "joy";
        // $admin->role = "subadmin";
        // $admin->mobile = "88000000000";
        // $admin->email = "joy@admin.com";
        // $admin->password = $password;
        // $admin->status=1;
        // $admin->save();

        // $admin =new Admin();
        // $admin->name = "anik";
        // $admin->role = "subadmin";
        // $admin->mobile = "88000000000";
        // $admin->email = "anik@admin.com";
        // $admin->password = $password;
        // $admin->status=1;
        // $admin->save();

    }
}
