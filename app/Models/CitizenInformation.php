<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullNameEN',
        'fathersNameEN',
        'mothersNameEN',
        'spouseNameEN',
        'presentAddressEN',
        'permenantAddressEN',
        'fullNameBN',
        'fathersNameBN',
        'mothersNameBN',
        'spouseNameBN',
        'presentAddressBN',
        'permanentAddressBN',
        'gender',
        'profession',
        'dateOfBirth',
        'birthPlaceBN',
        'mothersNationalityBN',
        'mothersNationalityEN',
        'fathersNationalityBN',
        'fathersNationalityEN',
        'birthRegistrationNumber',
        'nationalIdNumber',
        'oldNationalIdNumber',
        'photoUrl',
    ];

    protected $dates = [
        'dateOfBirth',
    ];

    // Optional: Define any relationships if needed

}
