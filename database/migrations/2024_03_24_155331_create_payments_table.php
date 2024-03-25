<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('externalId')->unique()->nullable();
            $table->decimal('value', 10, 2);
            $table->dateTime('dueDate');
            $table->string('description')->nullable();
            $table->string('status');
            $table->string('invoiceUrl')->nullable();
            $table->string('transactionReceiptUrl')->nullable();
            $table->string('remoteIp')->nullable();
            $table->string('creditCardNumber')->nullable();
            $table->string('creditCardHolderName')->nullable();
            $table->string('creditCardHolderEmail')->nullable();
            $table->string('creditCardHolderPhone')->nullable();
            $table->string('creditCardHolderCpfCnpj')->nullable();
            $table->string('creditCardHolderPostalCode')->nullable();
            $table->string('creditCardHolderAddressNumber')->nullable();
            $table->string('creditCardHolderAddressComplement')->nullable();
            $table->string('boletoUrl')->nullable();

            $table->string('customerId');
            $table->foreign('customerId')->references('externalId')->on('customers');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
