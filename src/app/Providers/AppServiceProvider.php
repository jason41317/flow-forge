<?php

namespace App\Providers;

use App\Events\LeadCreated;
use App\Listeners\LogLeadCreated;
use App\Support\Tenant\TenantManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            LeadCreated::class,
            LogLeadCreated::class,
        );
    }
}
