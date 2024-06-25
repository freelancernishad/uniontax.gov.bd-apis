<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sonods', function (Blueprint $table) {
            $table->id();
            $table->string('unioun_name')->nullable();
            $table->integer('year');
            $table->string('sonod_Id')->unique();
            $table->string('uniqeKey')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('sonod_name')->nullable();
            $table->string('successor_father_name')->nullable();
            $table->string('successor_mother_name')->nullable();
            $table->string('ut_father_name')->nullable();
            $table->string('ut_mother_name')->nullable();
            $table->string('ut_grame')->nullable();
            $table->string('ut_post')->nullable();
            $table->string('ut_thana')->nullable();
            $table->string('ut_district')->nullable();
            $table->string('ut_word')->nullable();
            $table->boolean('successor_father_alive_status')->nullable();
            $table->boolean('successor_mother_alive_status')->nullable();
            $table->string('applicant_holding_tax_number')->nullable();
            $table->string('applicant_national_id_number')->nullable();
            $table->string('applicant_birth_certificate_number')->nullable();
            $table->string('applicant_passport_number')->nullable();
            $table->date('applicant_date_of_birth')->nullable();
            $table->string('family_name')->nullable();
            $table->integer('Annual_income')->nullable();
            $table->string('Annual_income_text')->nullable();
            $table->boolean('Subject_to_permission')->nullable();
            $table->boolean('disabled')->nullable();
            $table->string('The_subject_of_the_certificate')->nullable();
            $table->string('Name_of_the_transferred_area')->nullable();
            $table->string('applicant_second_name')->nullable();
            $table->string('applicant_owner_type')->nullable();
            $table->string('applicant_name_of_the_organization')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('utname')->nullable();
            $table->string('ut_religion')->nullable();
            $table->boolean('alive_status')->nullable();
            $table->string('applicant_gender')->nullable();
            $table->string('applicant_marriage_status')->nullable();
            $table->string('applicant_vat_id_number')->nullable();
            $table->string('applicant_tax_id_number')->nullable();
            $table->string('applicant_type_of_business')->nullable();
            $table->string('applicant_type_of_businessKhat')->nullable();
            $table->integer('applicant_type_of_businessKhatAmount')->nullable();
            $table->string('applicant_father_name')->nullable();
            $table->string('applicant_mother_name')->nullable();
            $table->string('applicant_occupation')->nullable();
            $table->string('applicant_education')->nullable();
            $table->string('applicant_religion')->nullable();
            $table->string('applicant_resident_status')->nullable();
            $table->text('applicant_present_village')->nullable();
            $table->text('applicant_present_road_block_sector')->nullable();
            $table->text('applicant_present_word_number')->nullable();
            $table->text('applicant_present_district')->nullable();
            $table->text('applicant_present_Upazila')->nullable();
            $table->text('applicant_present_post_office')->nullable();
            $table->text('applicant_permanent_village')->nullable();
            $table->text('applicant_permanent_road_block_sector')->nullable();
            $table->text('applicant_permanent_word_number')->nullable();
            $table->text('applicant_permanent_district')->nullable();
            $table->text('applicant_permanent_Upazila')->nullable();
            $table->string('applicant_permanent_post_office')->nullable();
            $table->longText('successor_list')->nullable(); // Changed to longText
            $table->string('khat')->nullable();
            $table->integer('last_years_money')->nullable();
            $table->integer('currently_paid_money')->nullable();
            $table->integer('total_amount')->nullable();
            $table->text('amount_deails')->nullable();
            $table->string('the_amount_of_money_in_words')->nullable();
            $table->longText('applicant_mobile')->nullable(); // Changed to longText
            $table->string('applicant_email')->nullable();
            $table->longText('applicant_phone')->nullable(); // Changed to longText
            $table->string('applicant_national_id_front_attachment')->nullable();
            $table->string('applicant_national_id_back_attachment')->nullable();
            $table->string('applicant_birth_certificate_attachment')->nullable();
            $table->string('prottoyon')->nullable();
            $table->string('sec_prottoyon')->nullable();
            $table->string('stutus')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('chaireman_name')->nullable();
            $table->string('chaireman_type')->nullable();
            $table->string('c_email')->nullable();
            $table->string('chaireman_sign')->nullable();
            $table->string('socib_name')->nullable();
            $table->string('socib_signture')->nullable();
            $table->string('socib_email')->nullable();
            $table->string('cancedby')->nullable();
            $table->unsignedBigInteger('cancedbyUserid')->nullable();
            $table->unsignedBigInteger('pBy')->nullable();
            $table->string('sameNameNew')->nullable();
            $table->string('orthoBchor')->nullable();
            $table->string('format')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sonods');
    }
};
