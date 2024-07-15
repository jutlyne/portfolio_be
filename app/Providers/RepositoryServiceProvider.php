<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        App::bind(\App\Repositories\BlogRepository::class, \App\Repositories\BlogRepositoryEloquent::class);
        App::bind(\App\Repositories\TagRepository::class, \App\Repositories\TagRepositoryEloquent::class);
        App::bind(\App\Repositories\MediaRepository::class, \App\Repositories\MediaRepositoryEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
