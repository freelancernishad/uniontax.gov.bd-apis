<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeLicenseKhatFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_license_khat_fees', function (Blueprint $table) {
            $table->id();
            $table->string('khat_fee_id');
            $table->string('khat_id_1');
            $table->string('khat_id_2');
            $table->decimal('fee', 10, 2);
            $table->timestamps();

            // Indexes
            $table->unique('khat_fee_id'); // Assuming khat_fee_id should be unique
            $table->index(['khat_id_1', 'khat_id_2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_license_khat_fees');
    }
}
