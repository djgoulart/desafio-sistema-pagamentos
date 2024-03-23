<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoletoPaymentRequest;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PayWithBoletoController extends Controller
{
    protected $paymentProcessor;
    protected $customerService;

    public function __construct(PaymentProcessor $paymentProcessor, CustomerContract $customerService)
    {
        $this->paymentProcessor = $paymentProcessor;
        $this->customerService = $customerService;
    }

    public function handle(BoletoPaymentRequest $request): RedirectResponse
    {
        //dd($request->all());

        $customerData = new CustomerDto(
            name: $request->input('customerName'),
            cpfCnpj: $request->input('customerCpfCnpj'),
        );

        $customer = $this->customerService->createCustomer($customerData);

        if(!$customer) {
            return redirect(route('payment.register'))->with('error', 'Failed to create customer');
        }

        $paymentMethod = PaymentMethods::from($request->input('paymentMethod'));

        $payment = new PaymentDto(
            customerId: $customer['externalId'],
            value: $request->input('value'),
            dueDate: $request->input('dueDate'),
        );

        $paymentDetails = new PaymentDetailsDto(
            payment: $payment,
            remoteIp: $request->ip(),
        );

        $resp = $this->paymentProcessor->processPayment($request->input('paymentMethod'), $paymentDetails);

        dd($resp);

        return redirect(route('payment.register'));
    }
}
