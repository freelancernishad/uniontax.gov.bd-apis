<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoiceId');
            $table->unsignedBigInteger('holdingTax_id');
            $table->unsignedInteger('PayYear');
            $table->string('orthoBchor')->nullable();
            $table->decimal('totalAmount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('holdingTax_id')->references('id')->on('holding_tax');

            // Indexes
            $table->index('invoiceId');
            $table->index('PayYear');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_invoices');
    }
}
