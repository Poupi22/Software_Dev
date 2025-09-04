<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Database\Events\MigrationsEnded;
use App\Listeners\GeneratePermissionsAfterMigrate;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MigrationsEnded::class => [
            GeneratePermissionsAfterMigrate::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
