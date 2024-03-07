<?php

namespace App\Providers;

use App\Http\Mixins\ImageMixin;
use App\Models\Image;
use App\Models\User;
use Exception;
use Illuminate\Support\ServiceProvider;
use Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->isLocal()) {
            app()->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::mixin(new ImageMixin());
    }
}
