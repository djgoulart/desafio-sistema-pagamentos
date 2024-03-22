<?php

use App\Http\Controllers\Payment\RegisterPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('payment')->group(function () {
    Route::get('register', [RegisterPaymentController::class, 'create'])
                ->name('payment.register');
    
    Route::post('register', [RegisterPaymentController::class, 'store'])
                ->name('payment.store');

});
