import React, { useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, } from '@inertiajs/react';

export default function CreditCardResult() {

    return (
        <GuestLayout>
            <Head title="Payment Done!" />

            <div className='flex flex-col gap-4'>
                <h1>Pagamento Finalizado!</h1>
                <div className='flex gap-4 items-center'>
                    <Link
                        href={route('payment.register', { paymentMethod: 'CREDIT_CARD' })}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Fazer novo pagamento
                    </Link>
                </div>
                Seu pagamento foi processado com sucesso!
            </div>

        </GuestLayout>
    );
}
