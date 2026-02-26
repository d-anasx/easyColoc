<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Colocation;
use App\Models\Category;
use App\Models\Expense;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@easycoloc.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'is_banned' => false,
            'reputation' => 0,
        ]);

       User::factory(10)->create(['role_id' => $userRole->id]);

       
    }
}
