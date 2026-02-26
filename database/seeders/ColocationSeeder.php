<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colocation;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Expense;

class ColocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Colocation::factory(5)->create()->each(function ($colocation) {
            $users = User::where('role_id', Role::where('name', 'user')->first()->id)
                ->inRandomOrder()->take(4)->get();

            $colocation->members()->attach($users->first()->id, [
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            $users->skip(1)->each(fn($user) => $colocation->members()->attach($user->id, [
                'role' => 'member',
                'joined_at' => now(),
            ]));

            Category::factory(3)->create(['colocation_id' => $colocation->id]);

            
        });
    }
}
