<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Domain\Application\Payments\PaymentFactory;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use InvalidArgumentException;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        PaymentFactory::registerPaymentMethod(PaymentMethods::CREDIT_CARD->value, function ($data) {
            if (!$data->creditCard || !$data->remoteIp || !$data->creditCardHolderInfo) {
                throw new InvalidArgumentException('Credit card data, holder info and remote IP are required');
            }

            return new CreditCardPayment(
                payment: $data->payment,
                creditCard: $data->creditCard,
                creditCardHolderInfo: $data->creditCardHolderInfo,
                remoteIp: $data->remoteIp,
            );
        });
    }
}
