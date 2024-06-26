<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('union');
            $table->string('browser')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('device_name')->nullable();
            $table->string('roles')->nullable();
            $table->string('key')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('union');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
