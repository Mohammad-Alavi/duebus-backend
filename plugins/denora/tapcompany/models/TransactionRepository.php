<?php namespace Denora\TapCompany\Models;

use Carbon\Carbon;
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
     * @param int    $orderId
     * @param string $chargeId
     * @param string $paymentUrl
     * @param string $description
     *
     * @return Transaction
     */
    function create(int $orderId, string $chargeId, string $paymentUrl, string $description = '') {

        $transaction = new Transaction();
        $transaction->order_id = $orderId;
        $transaction->charge_id = $chargeId;
        $transaction->payment_url = $paymentUrl;
        $transaction->description = $description;

        $transaction->save();

        return $transaction;
    }

    function capture(int $doerId, string $chargeId){
        $transaction = $this->findByChargeId($chargeId);
        $transaction->paid_at = Carbon::now();
        $transaction->save();

        $transaction->order->setStatusPaid($doerId);
    }
}
