<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\FichePreinscription;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // On définit ici notre View Composer.
        View::composer('acceuil.layouts.partials.header', function ($view) {
            $fiche = FichePreinscription::first();
            $view->with('fichePreinscription', $fiche);
        });
    }
}
