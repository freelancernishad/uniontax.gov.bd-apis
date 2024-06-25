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
        Schema::create('holdingtaxes', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('unioun');
            $table->string('holding_no');
            $table->string('maliker_name');
            $table->string('father_or_samir_name');
            $table->string('gramer_name');
            $table->string('word_no');
            $table->string('nid_no');
            $table->string('mobile_no');
            $table->decimal('griher_barsikh_mullo', 10, 2);
            $table->decimal('barsikh_muller_percent', 5, 2);
            $table->decimal('jomir_vara', 10, 2);
            $table->decimal('total_mullo', 10, 2);
            $table->decimal('rokhona_bekhon_khoroch', 10, 2);
            $table->decimal('prakklito_mullo', 10, 2);
            $table->decimal('reyad', 10, 2);
            $table->decimal('angsikh_prodoy_korjoggo_barsikh_mullo', 10, 2);
            $table->decimal('barsikh_vara', 10, 2);
            $table->decimal('rokhona_bekhon_khoroch_percent', 5, 2);
            $table->decimal('prodey_korjoggo_barsikh_mullo', 10, 2);
            $table->decimal('prodey_korjoggo_barsikh_varar_mullo', 10, 2);
            $table->decimal('total_prodey_korjoggo_barsikh_mullo', 10, 2);
            $table->decimal('current_year_kor', 10, 2);
            $table->decimal('bokeya', 10, 2);
            $table->decimal('total_bokeya', 10, 2);
            $table->string('image')->nullable();
            $table->string('busnessName')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holdingtaxes');
    }
};
