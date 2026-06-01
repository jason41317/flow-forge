<?php

namespace App\Providers;

use App\Events\LeadCreated;
use App\Events\LeadUpdated;
use App\Listeners\DispatchLeadIntegrations;
use App\Listeners\DispatchLeadWebhook;
use App\Listeners\LogLeadCreated;
use App\Listeners\LogLeadUpdated;
use App\Listeners\NotifyLeadCreated;
use App\Services\Integrations\IntegrationProviderResolver;
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
        $this->app->singleton(IntegrationProviderResolver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(LeadCreated::class, LogLeadCreated::class);
        Event::listen(LeadCreated::class, NotifyLeadCreated::class);
        Event::listen(LeadCreated::class, DispatchLeadWebhook::class);
        Event::listen(LeadCreated::class, DispatchLeadIntegrations::class);

        Event::listen(LeadUpdated::class, LogLeadUpdated::class);
    }
}
