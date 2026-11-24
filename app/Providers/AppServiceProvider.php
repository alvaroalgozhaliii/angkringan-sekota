<?php

namespace App\Providers;

use App\Models\ProfilUsaha;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        Paginator::defaultView('pagination::admin');

        View::composer('admin.*', function ($view) {
            try {
                $view->with([
                    'profilGlobal' => ProfilUsaha::getSingleton(),
                ]);
            } catch (\Throwable $e) {
                $view->with([
                    'profilGlobal' => null,
                ]);
            }
        });
    }
}
