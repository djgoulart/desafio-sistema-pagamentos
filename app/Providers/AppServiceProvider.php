<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentService;
use App\Services\CustomerService;
use App\Services\PixService;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Application\Contracts\PixProcessor;
use Core\Domain\Application\Contracts\CustomerContract;
use App\Http\Controllers\PayWithBoletoController;
use Core\Domain\Application\Contracts\PaymentRepository;
use App\Repositories\BoletoPaymentEloquentRepository;
use App\Services\BoletoPaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //$this->app->bind(PaymentRepository::class, BoletoPaymentEloquentRepository::class);

        /* $this->app->when(PayWithBoletoController::class)
        ->needs(PaymentProcessor::class)
        ->give(BoletoPaymentService::class);

        $this->app->when(PayWithBoletoController::class)
        ->needs(PaymentRepository::class)
        ->give(BoletoPaymentEloquentRepository::class);

        $this->app->bind(PaymentProcessor::class, PaymentService::class); */
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
