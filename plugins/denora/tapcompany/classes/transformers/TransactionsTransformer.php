<?php namespace Denora\TapCompany\Classes\Transformers;

use Denora\TapCompany\Models\Transaction;

class TransactionsTransformer {

    /**
     * @param Transaction[] $transactions
     *
     * @return array
     */
    static function transform($transactions) {
        $array = [];

        if ($transactions == null) return $array;

        foreach ($transactions as $transaction) {
            array_push($array, TransactionTransformer::transform($transaction));
        }

        return $array;
    }

}