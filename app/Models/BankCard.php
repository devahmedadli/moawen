<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BankCard extends Model
{
    protected $fillable = [
        'user_id',
        'processor_token',
        'last_four_digits',
        'card_brand',
        'card_holder_name',
        'expiry_month',
        'expiry_year',
    ];

    // Only if you need to store encrypted card data
    public function setCardNumberAttribute($value)
    {
        $this->attributes['last_four_digits'] = substr($value, -4);
        $this->attributes['card_number_encrypted'] = Crypt::encryptString($value);
    }

    public function getCardNumberAttribute()
    {
        if (isset($this->attributes['card_number_encrypted'])) {
            return Crypt::decryptString($this->attributes['card_number_encrypted']);
        }
        return null;
    }
}
