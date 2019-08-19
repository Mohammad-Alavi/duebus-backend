<?php namespace Denora\TapCompany\Classes\Transformers;

use Denora\TapCompany\Models\Transaction;

class TransactionTransformer {

    /**
     * @param Transaction $transaction
     *
     * @return array
     */
    static function transform($transaction) {

        return [
            'id'            => $transaction->id,
            'chargeable'    => $transaction->chargeable,
            'chargeable_id' => $transaction->chargeable_id,
            'charge_id'     => $transaction->charge_id,
            'payment_url'   => $transaction->payment_url,
            'price'         => $transaction->price,
            'description'   => $transaction->description,
            'is_paid'       => (bool)$transaction->paid_at,

            'paid_at'    => $transaction->paid_at,
            'created_at' => $transaction->created_at,
            'updated_at' => $transaction->updated_at,
        ];

    }
}