<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charages', function (Blueprint $table) {
            $table->id();
            $table->string('district');
            $table->string('thana');
            $table->decimal('vat', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->string('service')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['district', 'thana']); // Example of indexing district and thana columns together
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charages');
    }
}
