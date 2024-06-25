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
            $table->id();
            $table->string('union');
            $table->string('trxId')->unique();
            $table->unsignedBigInteger('sonodId');
            $table->string('sonod_type');
            $table->decimal('amount', 10, 2);
            $table->string('applicant_mobile');
            $table->string('status');
            $table->date('date');
            $table->integer('month');
            $table->integer('year');
            $table->string('paymentUrl')->nullable();
            $table->text('ipnResponse')->nullable();
            $table->string('method');
            $table->string('payment_type');
            $table->decimal('balance', 10, 2)->nullable();
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
