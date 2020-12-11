<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap Services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register Services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\iEducar\Support\Repositories\StudentRepository::class, \App\Repositories\StudentRepositoryEloquent::class);
    }
}
