<?php namespace Denora\TapCompany\Classes\Repositories;

use Carbon\Carbon;
use Denora\TapCompany\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository {

    /**
     * @param int $id
     *
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    function find(int $id) {
        return Transaction::find($id);
    }

    /**
     * @param string $chargeId
     *
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    function findByChargeId(string $chargeId) {
        return Transaction::query()->where('charge_id', '=', $chargeId)->first();
    }

    /**
     * @param string $chargeable
     * @param int    $chargeableId
     * @param string $chargeId
     * @param string $paymentUrl
     * @param int    $price
     * @param int    $points
     * @param string $description
     *
     * @return Transaction
     */
    function createTransaction(string $chargeable, int $chargeableId, string $chargeId, string $paymentUrl, int $price, int $points, string $description = '') {

        $transaction = new Transaction();
        $transaction->chargeable = $chargeable;
        $transaction->chargeable_id = $chargeableId;
        $transaction->charge_id = $chargeId;
        $transaction->payment_url = $paymentUrl;
        $transaction->price = $price;
        $transaction->points = $points;
        $transaction->description = $description;

        $transaction->save();

        return $transaction;
    }

}