<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Client\ClientScraper;
use App\Services\Client\ClientScraperInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientScraperInterface::class, ClientScraper::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
