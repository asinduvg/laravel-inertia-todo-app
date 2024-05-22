<?php

namespace App\Providers;

use App\Contracts\TodoServiceContract;
use App\Http\Controllers\TodoController;
use App\Services\TodoService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->when(TodoController::class)
            ->needs(TodoServiceContract::class)
            ->give(TodoService::class);
    }
}
