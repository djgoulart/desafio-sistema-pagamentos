<?php

namespace App\Providers;

use App\Services\CustomerService;
use App\Services\PixService;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Application\Contracts\PixProcessor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PixProcessor::class, PixService::class);
        $this->app->bind(CustomerContract::class, CustomerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
