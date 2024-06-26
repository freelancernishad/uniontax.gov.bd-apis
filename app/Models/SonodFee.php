<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SonodFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'unioun',
        'service_id',
        'fees',
    ];
}
