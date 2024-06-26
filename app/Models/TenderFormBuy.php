<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderFormBuy extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_list_id',
        'name',
        'PhoneNumber',
        'form_code',
        'status',
    ];

    /**
     * Get the TenderList that owns the TenderFormBuy.
     */
    public function tenderList()
    {
        return $this->belongsTo(TenderList::class, 'tender_list_id', 'id');
    }
}
