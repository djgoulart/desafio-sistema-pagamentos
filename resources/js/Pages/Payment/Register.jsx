import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import BoletoForm from './Partials/BoletoForm';
import PixForm from './Partials/PixForm';
import CreditCardForm from './Partials/CreditCardForm';

export default function Register() {
    const paymentMethod = route().params.paymentMethod;
    const { data, setData, post, processing, errors, reset } = useForm({
        paymentMethod,
        value: '',
        date: new Date().toISOString().split('T')[0]
    });

    const submit = (e) => {
        e.preventDefault();
        setData('paymentMethod', paymentMethod);
        post(route('payment.store'));
    };

    return (
        <GuestLayout>
            <Head title="Register Payment" />

            <div className='flex flex-col gap-4'>
                <h1>Selecione um método de pagamento</h1>
                <div className='flex gap-4 items-center'>
                    <Link
                        href={route('payment.register', { paymentMethod: 'CREDIT_CARD' })}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Credit Card
                    </Link>
                    <Link
                        href={route('payment.register', { paymentMethod: 'BOLETO' })}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Boleto
                    </Link>
                    <Link
                        href={route('payment.register', { paymentMethod: 'PIX' })}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Pix
                    </Link>
                </div>

                {paymentMethod === 'BOLETO' && (<BoletoForm />)}
                {paymentMethod === 'PIX' && (<PixForm />)}
                {paymentMethod === 'CREDIT_CARD' && (<CreditCardForm />)}
            </div>

        </GuestLayout>
    );
}
