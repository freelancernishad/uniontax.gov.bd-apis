<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sonod_id');
            $table->string('user_id');
            $table->string('names');
            $table->string('position');
            $table->text('reason')->nullable();
            $table->string('union');
            $table->string('status');
            $table->timestamps();

            // Indexes
            $table->index('sonod_id');
            $table->index('user_id');
            $table->index('union');
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
        Schema::dropIfExists('action_logs');
    }
}
