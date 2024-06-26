<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizenInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen_information', function (Blueprint $table) {
            $table->id();
            $table->string('fullNameEN');
            $table->string('fathersNameEN')->nullable();
            $table->string('mothersNameEN')->nullable();
            $table->string('spouseNameEN')->nullable();
            $table->text('presentAddressEN')->nullable();
            $table->text('permenantAddressEN')->nullable();
            $table->string('fullNameBN')->nullable();
            $table->string('fathersNameBN')->nullable();
            $table->string('mothersNameBN')->nullable();
            $table->string('spouseNameBN')->nullable();
            $table->text('presentAddressBN')->nullable();
            $table->text('permanentAddressBN')->nullable();
            $table->string('gender')->nullable();
            $table->string('profession')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('birthPlaceBN')->nullable();
            $table->string('mothersNationalityBN')->nullable();
            $table->string('mothersNationalityEN')->nullable();
            $table->string('fathersNationalityBN')->nullable();
            $table->string('fathersNationalityEN')->nullable();
            $table->string('birthRegistrationNumber')->nullable();
            $table->string('nationalIdNumber')->nullable();
            $table->string('oldNationalIdNumber')->nullable();
            $table->string('photoUrl')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('fullNameEN');
            $table->index('nationalIdNumber');
            $table->index('dateOfBirth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizen_information');
    }
}
