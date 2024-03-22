import React, { useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';

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
        console.log(data);
        post(route('payment.store'));
    };

    useEffect(() => { console.log(errors) }, [errors]);

    return (
        <GuestLayout>
            <Head title="Register Payment" />

            <form onSubmit={submit} className='flex flex-col gap-4'>
                <TextInput name={"paymentMethod"} type={"hidden"} value={'OUTRO'} />
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
                <div className='flex flex-col gap-2'>
                    <InputLabel htmlFor="value" value="Value" />
                    <TextInput
                        id="value"
                        name="value"
                        type={"text"}
                        value={data.value}
                        onChange={(e) => setData('value', e.target.value)} />

                    <InputError message={errors.value} className="mt-2" />
                </div>
                <div className='flex flex-col gap-2'>
                    <InputLabel htmlFor="date" value="Date" />
                    <TextInput
                        id="date"
                        name="date"
                        type={"date"}
                        value={data.date}
                        onChange={(e) => setData('date', e.target.value)}
                        disabled
                        className={"disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"}
                    />

                    <InputError message={errors.date} className="mt-2" />
                </div>


                <div className="flex items-center justify-end mt-4">

                    <PrimaryButton className="ms-4" disabled={processing}>
                        Register
                    </PrimaryButton>
                </div>
                <InputError message={errors.paymentMethod} className="mt-2" />
            </form>
        </GuestLayout>
    );
}
