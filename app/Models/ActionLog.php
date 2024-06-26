<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sonod_id',
        'user_id',
        'names',
        'position',
        'reason',
        'union',
        'status',
    ];
}
