<?php

namespace App\Services;

use Core\Domain\Enterprise\Entities\Customer;
use Core\Domain\Application\Contracts\Customer as CustomerContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CustomerService implements CustomerContract
{
    public function createCustomer(CustomerDto $customer)
    {
        $apiUrl = Config::get('services.asaas.url');

        $requestData = [
            'name' => $customer->name,
            'cpfCnpj' => $customer->cpfCnpj,
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token')
        ])->post("$apiUrl/customers", $requestData);

        return $response->object();

        // Aqui, você pode adicionar mais lógica como salvar o pagamento no banco de dados,
        // enviar notificações, etc., dependendo dos requisitos da sua aplicação.

    }
}