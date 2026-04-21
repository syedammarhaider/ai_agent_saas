<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Auth\SimpleUserProvider;

class SimpleAuthProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::provider('simple', function ($app, array $config) {
            return new SimpleUserProvider();
        });
    }
}
