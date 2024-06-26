<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanderInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'union_name',
        'tanderid',
        'amount',
        'khat',
        'orthobochor',
        'status',
        'date',
        'year',
    ];

    protected $dates = [
        'date',
    ];
}
