<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_lists', function (Blueprint $table) {
            $table->id();
            $table->string('union_name')->nullable();
            $table->string('tender_id')->nullable();
            $table->string('tender_type')->nullable();
            $table->string('memorial_no')->nullable();
            $table->string('tender_name')->nullable();
            $table->text('description')->nullable();
            $table->string('tender_word_no')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('union')->nullable();
            $table->decimal('govt_price', 10, 2)->nullable();
            $table->decimal('form_price', 10, 2)->nullable();
            $table->integer('deposit_percent')->nullable();
            $table->date('noticeDate')->nullable();
            $table->date('form_buy_last_date')->nullable();
            $table->dateTime('tender_start')->nullable();
            $table->dateTime('tender_end')->nullable();
            $table->dateTime('tender_open')->nullable();
            $table->string('tender_roles')->nullable();
            $table->string('status')->nullable();
            $table->string('committe1name')->nullable();
            $table->string('committe1position')->nullable();
            $table->string('commette1phone')->nullable();
            $table->string('commette1pass')->nullable();
            $table->string('committe2name')->nullable();
            $table->string('committe2position')->nullable();
            $table->string('commette2phone')->nullable();
            $table->string('commette2pass')->nullable();
            $table->string('committe3name')->nullable();
            $table->string('committe3position')->nullable();
            $table->string('commette3phone')->nullable();
            $table->string('commette3pass')->nullable();
            $table->string('bankName')->nullable();
            $table->string('bankCheck')->nullable();
            $table->string('daysOfDepositeAmount')->nullable();
            $table->text('permitDetials')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_lists');
    }
}
