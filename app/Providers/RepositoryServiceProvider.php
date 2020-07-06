<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        App::bind(
            'App\Interfaces\UserRepositoryInterface',
            'App\Repositories\UserRepository'
        );

        App::bind(
            'App\Interfaces\OrderRepositoryInterface',
            'App\Repositories\OrderRepository'
        );
    }
}
