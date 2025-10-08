<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AIClient\AIClientInterface;
use App\Services\AIClient\OpenAIClient;
use App\Services\Client\ClientScraper;
use App\Services\Client\ClientScraperInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientScraperInterface::class, ClientScraper::class);
        $this->app->bind(AIClientInterface::class, OpenAIClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,
                    $query->bindings,
                );
            });
        }
    }
}
