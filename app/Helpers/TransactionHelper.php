<?php

namespace App\Helpers;

use App\Models\Transaction;

class TransactionHelper
{
    public static function generateUniqueTrxId(): string
    {
        $prefix = 'OBT-';

        do {
            $randomString = $prefix . mt_rand(100, 9999);
        } while (Transaction::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }
}
