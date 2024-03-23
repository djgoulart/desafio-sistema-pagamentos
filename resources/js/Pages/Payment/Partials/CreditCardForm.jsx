import React, { useEffect } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';
import CreditCardInput from '@/Components/CreditCardInput';
import PostalCodeInput from '@/Components/PostalCodeInput';

export default function CreditCardForm() {
    const paymentMethod = route().params.paymentMethod;
    const { data, setData, post, processing, errors, reset } = useForm({
        customerName: '',
        customerCpfCnpj: '',
        paymentMethod,
        value: '',
        dueDate: new Date().toISOString().split('T')[0],
        cardHolderName: '',
        cardNumber: '',
        cardExpiryMonth: '',
        cardExpiryYear: '',
        cardCvv: '',
        holderInfoName: '',
        holderInfoEmail: '',
        holderInfoCpfCnpj: '',
        holderInfoPostalCode: '',
        holderInfoAddressNumber: '',
        holderInfoAddressComplement: '',
        holderInfoPhone: '',
    });


    const submit = (e) => {
        e.preventDefault();

        setData('paymentMethod', paymentMethod);
        console.log(data);
        post(route('payment.pay.credit-card'));
    };

    // useEffect(() => { console.log(data.cardNumber) }, [data.cardNumber]);

    return (
        <form onSubmit={submit} className='flex flex-col gap-4'>
            <TextInput name={"paymentMethod"} type={"hidden"} value={data.paymentMethod} />

            <h3 className='text-lg font-semibold'>Dados do Cliente</h3>
            <div className='flex flex-col gap-1'>
                <InputLabel htmlFor="customerName" value="Nome" />
                <TextInput
                    id="customerName"
                    name="customerName"
                    type={"text"}
                    value={data.customerName}
                    onChange={(e) => setData('customerName', e.target.value)} />

                <InputError message={errors.customerName} className="mt-2" />
            </div>

            <div className='flex flex-col gap-1'>
                <InputLabel htmlFor="customerCpfCnpj" value="Cpf/Cnpj" />
                <TextInput
                    id="customerCpfCnpj"
                    name="customerCpfCnpj"
                    type={"text"}
                    value={data.customerCpfCnpj}
                    onChange={(e) => setData('customerCpfCnpj', e.target.value)} />

                <InputError message={errors.customerCpfCnpj} className="mt-2" />
            </div>

            <h3 className='text-lg font-semibold'>Dados do Pagamento</h3>
            <div className='flex flex-col gap-1'>
                <InputLabel htmlFor="value" value="Valor" />
                <TextInput
                    id="value"
                    name="value"
                    type={"text"}
                    value={data.value}
                    onChange={(e) => setData('value', e.target.value)} />

                <InputError message={errors.value} className="mt-2" />
            </div>
            <div className='flex flex-col gap-1'>
                <InputLabel htmlFor="dueDate" value="Data de Vencimento" />
                <TextInput
                    id="dueDate"
                    name="dueDate"
                    type={"date"}
                    value={data.dueDate}
                    onChange={(e) => setData('dueDate', e.target.value)}
                    disabled
                    className={"disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"}
                />

                <InputError message={errors.dueDate} className="mt-2" />
            </div>

            <h3 className='text-lg font-semibold'>Dados do Cartão</h3>

            {/* Card Number */}
            <div className='flex flex-col gap-1'>
                <InputLabel htmlFor="cardNumber" value="Número" />
                <CreditCardInput
                    id="cardNumber"
                    name="cardNumber"
                    type="text"
                    value={data.cardNumber}
                    placeholder="0000 0000 0000 0000"
                    onChange={(e) => setData('cardNumber', e.target.value)} />
                <InputError message={errors.cardNumber} className="mt-2" />
            </div>
            {/* Card Expiry */}
            <div className='flex items-center gap-4'>
                {/* Card Expiry Month */}
                <div className='flex flex-col gap-1'>
                    <InputLabel htmlFor="cardExpiryMonth" value="Mês" />
                    <TextInput
                        id="cardExpiryMonth"
                        name="cardExpiryMonth"
                        type="text"
                        value={data.cardExpiryMonth}
                        maxLength={2}
                        placeholder="MM"
                        onChange={(e) => setData('cardExpiryMonth', e.target.value)}
                        className={"max-w-16 text-center"} />
                </div>

                {/* Card Expiry Year */}
                <div className='flex flex-col gap-1'>
                    <InputLabel htmlFor="cardExpiryYear" value="Ano" />
                    <TextInput
                        id="cardExpiryYear"
                        name="cardExpiryYear"
                        type="text"
                        value={data.cardExpiryYear}
                        maxLength={4}
                        placeholder="YYYY"
                        onChange={(e) => setData('cardExpiryYear', e.target.value)}
                        className={"max-w-16 text-center"} />
                </div>

                <div className='flex flex-col gap-1 ml-10'>
                    <InputLabel htmlFor="cardCvv" value="CVV" />
                    <TextInput
                        id="cardCvv"
                        name="cardCvv"
                        type="text"
                        value={data.cardCvv}
                        maxLength={4}
                        placeholder="cvv"
                        onChange={(e) => setData('cardCvv', e.target.value)}
                        className={"max-w-16"} />
                </div>
                <InputError message={errors.cardExpiryMonth} className="mt-2" />
                <InputError message={errors.cardExpiryYear} className="mt-2" />
                <InputError message={errors.cardCvv} className="mt-2" />
            </div>

            {/* Card Holder Name */}
            <div className='flex flex-col gap-1'>
                <InputLabel htmlFor="cardHolderName" value="Nome no Cartão" />
                <TextInput
                    id="cardHolderName"
                    name="cardHolderName"
                    type="text"
                    value={data.cardHolderName}
                    placeholder="Nome impresso no cartão"
                    onChange={(e) => setData('cardHolderName', e.target.value)} />
                <InputError message={errors.cardHolderName} className="mt-2" />
            </div>


            {/* Holder Info Email */}
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="holderInfoEmail" value="E-mail" />
                <TextInput
                    id="holderInfoEmail"
                    name="holderInfoEmail"
                    type="email"
                    value={data.holderInfoEmail}
                    placeholder="Email do titular do cartão"
                    onChange={(e) => setData('holderInfoEmail', e.target.value)} />
                <InputError message={errors.holderInfoEmail} className="mt-2" />
            </div>

            {/* Holder Info Cpf/Cnpj */}
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="holderInfoCpfCnpj" value="Cpf/Cnpj" />
                <TextInput
                    id="holderInfoCpfCnpj"
                    name="holderInfoCpfCnpj"
                    type="text"
                    value={data.holderInfoCpfCnpj}
                    placeholder="CPF ou CNPJ do titular do cartão"
                    onChange={(e) => setData('holderInfoCpfCnpj', e.target.value)} />
                <InputError message={errors.holderInfoCpfCnpj} className="mt-2" />
            </div>

            {/* Holder Info Postal Code */}
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="holderInfoPostalCode" value="CEP" />
                <PostalCodeInput
                    id="holderInfoPostalCode"
                    name="holderInfoPostalCode"
                    type="text"
                    value={data.holderInfoPostalCode}
                    placeholder="CEP do titular do cartão"
                    onChange={(e) => setData('holderInfoPostalCode', e.target.value)} />
                <InputError message={errors.holderInfoPostalCode} className="mt-2" />
            </div>

            {/* Holder Info Address Complement */}
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="holderInfoAddressComplement" value="Endereço" />
                <TextInput
                    id="holderInfoAddressComplement"
                    name="holderInfoAddressComplement"
                    type="text"
                    value={data.holderInfoAddressComplement}
                    placeholder="Endereço"
                    onChange={(e) => setData('holderInfoAddressComplement', e.target.value)} />
                <InputError message={errors.holderInfoAddressComplement} className="mt-2" />
            </div>

            {/* Holder Info Address Number */}
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="holderInfoAddressNumber" value="Número" />
                <TextInput
                    id="holderInfoAddressNumber"
                    name="holderInfoAddressNumber"
                    type="text"
                    value={data.holderInfoAddressNumber}
                    placeholder="Ex: 999"
                    maxLength={6}
                    onChange={(e) => setData('holderInfoAddressNumber', e.target.value)} />
                <InputError message={errors.holderInfoAddressNumber} className="mt-2" />
            </div>

            {/* Holder Info Phone */}
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="holderInfoPhone" value="Telefone" />
                <TextInput
                    id="holderInfoPhone"
                    name="holderInfoPhone"
                    type="tel"
                    value={data.holderInfoPhone}
                    placeholder="Somente números. Ex: 11999999999"
                    onChange={(e) => setData('holderInfoPhone', e.target.value)} />
                <InputError message={errors.holderInfoPhone} className="mt-2" />
            </div>

            <div className="flex items-center justify-end mt-4">

                <PrimaryButton className="ms-4" disabled={processing}>
                    {processing ? 'Processando...' : 'Pagar'}
                </PrimaryButton>
            </div>
            <InputError message={errors.paymentMethod} className="mt-2" />
        </form>
    );
}
