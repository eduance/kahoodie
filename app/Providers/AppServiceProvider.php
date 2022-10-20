<?php

namespace App\Providers;

use App\Kahoodie\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return sprintf('Database\Factories\%sFactory', class_basename($modelName));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
