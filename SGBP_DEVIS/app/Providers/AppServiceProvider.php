<?php

namespace App\Providers;

use App\Models\Parametre;
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
    $db = config('database.connections.sqlite.database');

    if ($db && !file_exists($db)) {
        touch($db);
    }

    if (\Illuminate\Support\Facades\Schema::hasTable('parametres')) {
        View::share('parametre', Parametre::get());
    } else {
        View::share('parametre', collect());
    }
}
}