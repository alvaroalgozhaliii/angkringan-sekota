<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pesan;
use App\Models\ProfilUsaha;

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
        // Share notification data and business profile with all admin views
        View::composer('admin.*', function ($view) {
            try {
                $unreadCount = Pesan::where('status', 'belum_dibaca')->count();
                $recentPesan = Pesan::latest()->take(5)->get();
                $profilGlobal = ProfilUsaha::getSingleton();

                $view->with([
                    'notifUnreadCount' => $unreadCount,
                    'notifRecentPesan' => $recentPesan,
                    'profilGlobal'     => $profilGlobal,
                ]);
            } catch (\Throwable $e) {
                // In case migrations are running or DB is not yet initialized
                $view->with([
                    'notifUnreadCount' => 0,
                    'notifRecentPesan' => collect(),
                    'profilGlobal'     => null,
                ]);
            }
        });
    }
}
