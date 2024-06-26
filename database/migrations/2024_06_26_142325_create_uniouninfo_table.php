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
        Schema::create('uniouninfo', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('short_name_e');
            $table->string('domain')->nullable();
            $table->string('portal')->nullable();
            $table->string('short_name_b')->nullable();
            $table->string('thana')->nullable();
            $table->string('district')->nullable();
            $table->string('web_logo')->nullable();
            $table->string('sonod_logo')->nullable();
            $table->string('c_signature')->nullable();
            $table->string('c_name')->nullable();
            $table->string('c_type')->nullable();
            $table->string('c_email')->nullable();
            $table->string('socib_name')->nullable();
            $table->string('socib_signature')->nullable();
            $table->string('socib_email')->nullable();
            $table->string('format')->nullable();
            $table->string('u_image')->nullable();
            $table->text('u_description')->nullable();
            $table->text('u_notice')->nullable();
            $table->string('u_code')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('google_map')->nullable();
            $table->string('defaultColor')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('AKPAY_MER_REG_ID')->nullable();
            $table->string('AKPAY_MER_PASS_KEY')->nullable();
            $table->integer('smsBalance')->nullable();
            $table->boolean('nidServicestatus')->default(false);
            $table->string('nidService')->nullable();
            $table->boolean('status')->default(true);
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uniouninfo');
    }
};
