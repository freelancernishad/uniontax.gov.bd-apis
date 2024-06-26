<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('unioun_name');
            $table->date('date');
            $table->unsignedInteger('month');
            $table->unsignedInteger('year');
            $table->decimal('price', 10, 2);
            $table->string('description')->nullable();
            $table->decimal('balance', 10, 2)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('date');
            $table->index('unioun_name');
            $table->index(['month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenditures');
    }
}
