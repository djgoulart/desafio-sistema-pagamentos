<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Enterprise\Dtos\CreditCardDto;
use Core\Domain\Enterprise\Dtos\CreditCardHolderInfoDto;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Entities\Payment;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\PaymentService;

class PayWithCreditCardController extends Controller
{
    protected $paymentProcessor;
    protected $customerService;

    public function __construct(
        PaymentService $paymentProcessor,
        CustomerContract $customerService
    ) {
        $this->paymentProcessor = $paymentProcessor;
        $this->customerService = $customerService;
    }

    public function handle(StorePaymentRequest $request): RedirectResponse
    {
        $customerData = new CustomerDto(
            name: $request->input('customerName'),
            cpfCnpj: $request->input('customerCpfCnpj'),
        );

        $customer = $this->customerService->createCustomer($customerData);

        if(!$customer) {
            return redirect(route('payment.register'))->with('error', 'Failed to create customer');
        }

        $paymentMethod = PaymentMethods::from($request->input('paymentMethod'));

        $paymentData = new PaymentDto(
            customerId: $customer['externalId'],
            value: $request->input('value'),
            dueDate: $request->input('dueDate'),
        );

        $creditCard = new CreditCardDto(
            holderName: $request->input('cardHolderName'),
            number:str_replace(' ', '', $request->input('cardNumber')),
            expiryMonth: $request->input('cardExpiryMonth'),
            expiryYear: $request->input('cardExpiryYear'),
            ccv: $request->input('cardCvv'),
        );

        $creditCardHolderInfo = new CreditCardHolderInfoDto(
            name: $request->input('cardHolderName'),
            cpfCnpj: $request->input('holderInfoCpfCnpj'),
            postalCode: $request->input('holderInfoPostalCode'),
            addressComplement: $request->input('holderInfoAddressComplement'),
            addressNumber: $request->input('holderInfoAddressNumber'),
            email: $request->input('holderInfoEmail'),
            phone: $request->input('holderInfoPhone'),
        );

        $paymentDetails = new PaymentDetailsDto(
            payment: $paymentData,
            creditCard: $creditCard,
            creditCardHolderInfo: $creditCardHolderInfo,
            remoteIp: $request->ip(),
        );

        $payment = $this->paymentProcessor->processPayment(
            $request->input('paymentMethod'),
            $paymentDetails
        );

        if(!$payment) {
            return redirect(route('payment.register'))->with('error', 'Failed to process payment');
        }

        //$resultData = $payment->getData();

        return redirect()->route('payment.pay.credit-card.result', [
            'payment' => $payment
        ]);

    }

    public function result(): Response
    {
        return Inertia::render('Payment/CreditCardResult');
    }
}
