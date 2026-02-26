<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Colocation::all()->each(function ($colocation) {
            $members = $colocation->members;
            $categories = $colocation->categories;

            Expense::factory(10)->create([
                'colocation_id' => $colocation->id,
                'created_by'    => $members->random()->id,
                'category_id'   => $categories->random()->id,
            ]);
        });

        $expenses = Expense::all();
        $users = User::whereHas('colocations')->get();

        foreach ($users as $user) {
            $expenses->random(5)->each(fn($expense) => $user->expenses()->attach($expense->id, [
                'is_paid' => $expense->created_by === $user->id ? true : fake()->boolean(50),
            ]));
        }
    }
}
