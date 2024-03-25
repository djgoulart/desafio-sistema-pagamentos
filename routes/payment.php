<?php

use App\Http\Controllers\Payment\RegisterPaymentController;
use App\Http\Controllers\Payment\PayWithCreditCardController;
use App\Http\Controllers\Payment\PayWithBoletoController;
use App\Http\Controllers\Payment\PayWithPixController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('payment')->group(function () {
    Route::get('register', [RegisterPaymentController::class, 'handle'])
        ->name('payment.register');

    Route::post('pay/cc', [PayWithCreditCardController::class, 'handle'])
        ->name('payment.pay.credit-card');

    Route::get('pay/cc/result/{paymentId}', [PayWithCreditCardController::class, 'result'])
        ->name('payment.pay.credit-card.result');

    Route::post('pay/boleto', [PayWithBoletoController::class, 'handle'])
        ->name('payment.pay.boleto');

    Route::get('pay/boleto/result/{paymentId}', [PayWithBoletoController::class, 'result'])
        ->name('payment.pay.boleto.result');

    Route::post('pay/pix', [PayWithPixController::class, 'handle'])
        ->name('payment.pay.pix');

    Route::get('pay/pix/result/{paymentId}', [PayWithPixController::class, 'result'])
        ->name('payment.pay.pix.result');

});
