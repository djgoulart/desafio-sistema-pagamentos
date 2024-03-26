<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class RegisterPaymentController extends Controller
{
    public function handle(): Response
    {
        return Inertia::render('Payment/Register');
    }
}
