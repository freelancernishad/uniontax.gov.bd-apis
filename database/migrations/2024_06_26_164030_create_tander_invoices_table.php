<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTanderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tander_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('union_name');
            $table->unsignedBigInteger('tanderid');
            $table->decimal('amount', 10, 2);
            $table->string('khat')->nullable();
            $table->string('orthobochor')->nullable();
            $table->string('status')->default('pending');
            $table->date('date')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('union_name');
            $table->index('tanderid');
            $table->index('status');
            $table->index('date');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tander_invoices');
    }
}
