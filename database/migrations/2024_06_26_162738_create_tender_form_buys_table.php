<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderFormBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_form_buys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_list_id')->constrained('tender_lists')->onDelete('cascade');
            $table->string('name');
            $table->string('PhoneNumber');
            $table->string('form_code');
            $table->string('status')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('tender_list_id');
            $table->index('form_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_form_buys');
    }
}
