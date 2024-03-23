<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Rule;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Support\Facades\Http;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Dtos\CreditCardDto;
use Core\Domain\Enterprise\Dtos\CreditCardHolderInfoDto;

class RegisterPaymentController extends Controller
{
    protected $paymentProcessor;

    public function __construct(PaymentProcessor $paymentProcessor) {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function create(): Response
    {
        return Inertia::render('Payment/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $paymentMethod = PaymentMethods::from($request->input('paymentMethod'));

        $payment = new PaymentDto(
            customerId: 'cus_000005931635',
            value: $request->input('value'),
            dueDate: $request->input('date'),
        );

        $creditCard = new CreditCardDto(
            holderName: 'john doe',
            number:'5162306219378829',
            expiryMonth: '05',
            expiryYear: '2025',
            ccv: '123',
        );

        $creditCardHolderInfo = new CreditCardHolderInfoDto(
            name: 'John Doe',
            cpfCnpj: '24971563792',
            postalCode: '89223-005',
            addressNumber: '277',
            email: 'john.doe@asaas.com.br',
            phone: '4738010919',
        );

        $paymentDetails = new PaymentDetailsDto(
            payment: $payment,
            creditCard: $creditCard,
            creditCardHolderInfo: $creditCardHolderInfo,
            remoteIp: $request->ip(),
        );

        $resp = $this->paymentProcessor->processPayment($request->input('paymentMethod'), $paymentDetails);

        dd($resp);


        return redirect(route('payment.register'));
    }
}
