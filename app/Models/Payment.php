<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'union', 'trxId', 'sonodId', 'sonod_type', 'amount', 'applicant_mobile', 'status', 'date', 'month', 'year', 'paymentUrl', 'ipnResponse', 'method', 'payment_type', 'balance'
    ];

    public function sonodable()
    {
        return $this->morphTo();
    }


    public function getSonod()
    {
        switch ($this->sonod_type) {
            case 'sonod':
                return $this->belongsTo(Sonod::class, 'sonodId');
            case 'holding_tax':
                return $this->belongsTo(HoldingTax::class, 'sonodId');
            case 'holding_bokeya':
                return $this->belongsTo(HoldingBokeya::class, 'sonodId');
            // Add more cases as needed
            default:
                return null;
        }
    }
    }

}
