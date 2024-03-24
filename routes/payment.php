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

    Route::post('pay/boleto', [PayWithBoletoController::class, 'handle'])
    ->name('payment.pay.boleto');

    Route::post('pay/pix', [PayWithPixController::class, 'handle'])
    ->name('payment.pay.pix');

});
