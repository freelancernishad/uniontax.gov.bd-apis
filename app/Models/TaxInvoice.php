<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxInvoice extends Model
{
    protected $fillable = [
        'invoiceId',
        'holdingTax_id',
        'PayYear',
        'orthoBchor',
        'totalAmount',
        'status',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the holding tax that owns the tax invoice.
     */
    public function holdingTax()
    {
        return $this->belongsTo(HoldingTax::class, 'holdingTax_id', 'id');
    }
}
