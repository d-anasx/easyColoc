<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Colocation;
use App\Models\User;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'colocation_id' => Colocation::factory(),
            'paid_by' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->words(3, true),
            'amount' => fake()->randomFloat(2, 5, 500),
            'created_at' => now(),
        ];
    }
}
