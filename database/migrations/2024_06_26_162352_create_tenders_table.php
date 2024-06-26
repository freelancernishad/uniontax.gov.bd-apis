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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_lists')->onDelete('cascade');
            $table->string('dorId')->nullable();
            $table->string('nidNo')->nullable();
            $table->date('nidDate')->nullable();
            $table->string('applicant_orgName')->nullable();
            $table->string('applicant_org_fatherName')->nullable();
            $table->string('vill')->nullable();
            $table->string('postoffice')->nullable();
            $table->string('thana')->nullable();
            $table->string('distric')->nullable();
            $table->string('mobile')->nullable();
            $table->decimal('DorAmount', 10, 2)->nullable();
            $table->string('DorAmountText')->nullable();
            $table->decimal('depositAmount', 10, 2)->nullable();
            $table->string('bank_draft_image')->nullable();
            $table->text('deposit_details')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('tender_id');
            $table->index('dorId');
            $table->index('nidNo');
            $table->index('distric');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
