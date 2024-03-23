<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PixPaymentRequest;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Application\Contracts\PixProcessor;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PayWithPixController extends Controller
{
    protected $customerService;
    protected $paymentProcessor;
    protected $pixProcessor;

    public function __construct(
        CustomerContract $customerService,
        PaymentProcessor $paymentProcessor,
        PixProcessor $pixProcessor
    )
    {
        $this->customerService = $customerService;
        $this->paymentProcessor = $paymentProcessor;
        $this->pixProcessor = $pixProcessor;
    }

    public function handle(PixPaymentRequest $request): RedirectResponse
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

        $payment = $this->paymentProcessor->processPayment($request->input('paymentMethod'), $paymentDetails);
        $withPixDetails = $this->pixProcessor->getPixData($payment);

        dd($withPixDetails);

        return redirect(route('payment.register'));
    }
}
