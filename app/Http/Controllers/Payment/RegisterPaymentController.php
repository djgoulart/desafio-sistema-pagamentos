<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Inertia\Inertia;
use Inertia\Response;

class RegisterPaymentController extends Controller
{
    protected $paymentProcessor;
    protected $customerService;

    public function __construct(PaymentProcessor $paymentProcessor, CustomerContract $customerService) {
        $this->paymentProcessor = $paymentProcessor;
        $this->customerService = $customerService;
    }

    public function handle(): Response
    {
        return Inertia::render('Payment/Register');
    }

}
