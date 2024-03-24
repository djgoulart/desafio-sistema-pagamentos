<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentService;
use App\Services\CustomerService;
use App\Services\PixService;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Application\Contracts\PixProcessor;
use Core\Domain\Application\Contracts\CustomerContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentProcessor::class, PaymentService::class);
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
