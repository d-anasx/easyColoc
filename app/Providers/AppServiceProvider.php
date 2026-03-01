<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    

public function boot(): void
{
    Gate::policy(\App\Models\Colocation::class, \App\Policies\ColocationPolicy::class);
    Gate::policy(\App\Models\Expense::class, \App\Policies\ExpensePolicy::class);
    Gate::policy(\App\Models\Category::class, \App\Policies\CategoryPolicy::class);
}
}
