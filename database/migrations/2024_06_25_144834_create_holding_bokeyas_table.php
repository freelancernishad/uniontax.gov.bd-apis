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
        Schema::create('holding_bokeyas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('holdingTax_id');
            $table->integer('year');
            $table->decimal('price', 10, 2);
            $table->integer('payYear');
            $table->string('payOB');
            $table->string('status');
            $table->timestamps();

            $table->foreign('holdingTax_id')->references('id')->on('holdingtaxes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holding_bokeyas');
    }
};
