<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeLicenseKhatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_license_khats', function (Blueprint $table) {
            $table->id();
            $table->string('khat_id');
            $table->string('name');
            $table->string('main_khat_id')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique('khat_id'); // Assuming khat_id should be unique
            $table->index('main_khat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_license_khats');
    }
}
