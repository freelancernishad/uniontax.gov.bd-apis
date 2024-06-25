<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoldingTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'unioun',
        'holding_no',
        'maliker_name',
        'father_or_samir_name',
        'gramer_name',
        'word_no',
        'nid_no',
        'mobile_no',
        'griher_barsikh_mullo',
        'barsikh_muller_percent',
        'jomir_vara',
        'total_mullo',
        'rokhona_bekhon_khoroch',
        'prakklito_mullo',
        'reyad',
        'angsikh_prodoy_korjoggo_barsikh_mullo',
        'barsikh_vara',
        'rokhona_bekhon_khoroch_percent',
        'prodey_korjoggo_barsikh_mullo',
        'prodey_korjoggo_barsikh_varar_mullo',
        'total_prodey_korjoggo_barsikh_mullo',
        'current_year_kor',
        'bokeya',
        'total_bokeya',
        'image',
        'busnessName'
    ];

    public function holdingBokeyas()
    {
        return $this->hasMany(HoldingBokeya::class);
    }
}
