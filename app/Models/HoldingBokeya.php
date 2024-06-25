<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoldingBokeya extends Model
{
    use HasFactory;

    protected $fillable = [
        'holdingTax_id',
        'year',
        'price',
        'payYear',
        'payOB',
        'status'
    ];

    public function holdingTax()
    {
        return $this->belongsTo(HoldingTax::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'sonodable');
    }
}
