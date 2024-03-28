## Sobre o Projeto

Esta é uma aplicação desenvolvida para a realização de um desafio técnico as especificações abaixo:

<p><b>Objetivo:</b> O desafio é desenvolver um sistema de processamento de pagamentos integrado ao ambiente de homologação do Asaas, levando em consideração que o cliente deve acessar uma página onde irá selecionar a opção de pagamento entre Boleto, Cartão ou Pix.</p>

<p>Basicamente, o sistema deve ter a opção do pagamento e um formulário com inputs necessários para processar o pagamento e um botão 'finalizar pagamento', e se o pagamento der certo direcionar para uma página de obrigado.</p>

## Regras do Desafio
- O sistema deverá ser desenvolvido utilizando a linguagem PHP no framework Laravel.
  - Tratar os Dados no Request da requisição para que não aconteça de vir dados faltando ou diferentes do necessário.
  - Resposta da solicitação ser padronizada via Resources conforme o necessário.
  - Padronização das Requisições das APIs de integração com o Asaas.

- Processamento de pagamentos com boleto, cartão de crédito e pix.
  - Se o pagamento for boleto mostrar um botão com o link do boleto na página de obrigado.
  - Se o pagamento for Pix exibir o QRCode e o Copia e Cola na página de obrigado.
  - Em caso de recusa do cartão ou erro na requisição mostrar uma mensagem amigável no retorno para facilitar o entendimento do não processamento do pagamento.

- Não é necessário se importar com a qualidade do front, usar um bootstrap bem básico

- Utilize boas práticas de programação

- Utilize boas práticas de git

- Documentar como rodar o projeto

## Solução do Desafio

<img src="https://github.com/djgoulart/desafio-sistema-pagamentos/blob/be467a323b34430bd53e17f9eb2faa878f9e98ce/docs/screen-boleto.png" width="800" />
<br />
O projeto foi desenvolvido utilizando uma estrutura análoga ao DDD, um pouco mais enxuta dada a natureza do mesmo. Foram implementados testes unitários para boa parte do código que está relacionado ao Domínio da aplicação. 

### Stack utilizada
- Php
- Laravel
- ReactJS

### Estrutura do Projeto

- <b>/src:</b> é a pasta que armazena a maior parte do código da aplicação, no intuito de mantê-la desacoplada do Laravel em boa parte dos cenários.
- <b>/app/Services:</b> Contém os arquivos dos serviços que são responsáveis pela comunicação com a API de pagamentos.
- <b>/app/Repositories:</b> Contém os arquivos responsáveis pela comunicação com a camada de dados da aplicação, atualmente o Eloquent.
  - Apesar do nome, essas classes não implementam de fato o pattern repository com injeção e inversão de dependências, pois elas não seguem a implementação de nenhuma interface.
- <b>/app/Http/Controllers/Payment:</b> Os controllers das rotas de pagamento estão definidos aqui.
- <b>/app/Http/Requests:</b> Os validadores para as requests de cada endpoint de pagamento estão definidos nessa pasta.
- <b>/app/Models:</b> Os models da camada de dados aqui.
- <b>/routes/payment.php:</b> Todas as rotas de pagamento da aplicação estão nesse arquivo.

## Como executar a aplicação em ambiente local:
Para que a aplicação funcione corretamente você precisará de uma chave de autenticação do ambiente SandBox da API Asaas.

### Requisitos
- Docker
- Docker Compose
- Php 8.2
- Composer

Crie um arquivo ```.env``` baseado no arquivo de exemplo ```.env.example``` e adicione sua chave de autenticação da API Asaas juntamente com a URL da api:

```env
ASAAS_API_KEY
ASAAS_API_URL
```

Inicialize o projeto utilizando Laravel Sail
```shell
./vendor/bin/sail composer install
```
```shell
./vendor/bin/sail up -d
```
Crie a estrutura do banco de dados
```shell
./vendor/bin/sail artisan migrate
```
Hora de buildar os arquivos do frontend

```shell
pnpm build
```

Acesse a tela inicial de pagamentos em:
[http://localhost](http://localhost)

## Rodando os testes
```shell
./vendor/bin/sail artisan test
```


## License

[MIT license](https://opensource.org/licenses/MIT).
