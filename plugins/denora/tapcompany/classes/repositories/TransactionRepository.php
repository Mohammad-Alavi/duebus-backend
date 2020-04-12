<?php namespace Denora\TapCompany\Classes\Repositories;

use Denora\TapCompany\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{

    /**
     * @param int $id
     *
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    function find(int $id)
    {
        return Transaction::find($id);
    }

    /**
     * @param string $chargeId
     *
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    function findByChargeId(string $chargeId)
    {
        return Transaction::query()->where('charge_id', '=', $chargeId)->first();
    }

    /**
     * @param int $businessId
     * @return Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    function findBusinessTransaction(int $businessId)
    {
        return Transaction::query()
            ->where('chargeable', '=', 'business')
            ->where('chargeable_id', '=', $businessId)
            ->whereNotNull('paid_at')
            ->first();
    }

    /**
     * @param int $userId
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    function findPaidWalletTransactions(int $userId)
    {
        return Transaction::query()
            ->where('chargeable', '=', 'wallet')
            ->where('chargeable_id', '=', $userId)
            ->whereNotNull('paid_at')
            ->get();
    }

    /**
     * @param int $userId
     * @param $packageId
     * @param string $chargeable
     * @param int $chargeableId
     * @param $walletPayload
     * @param $inquiryPayload
     * @param $chargeId
     * @param $paymentUrl
     * @param int $price
     * @param string $redirectUrl
     * @param string $description
     *
     * @return Transaction
     */
    function createTransaction(
        int $userId,
        $packageId,
        string $chargeable,
        int $chargeableId,
        $walletPayload,
        $inquiryPayload,
        $chargeId,
        $paymentUrl,
        int $price,
        string $redirectUrl,
        string $description = ''
    )
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->package_id = $packageId;
        $transaction->chargeable = $chargeable;
        $transaction->chargeable_id = $chargeableId;
        $transaction->wallet_payload = $walletPayload;
        $transaction->inquiry_payload = $inquiryPayload;
        $transaction->charge_id = $chargeId;
        $transaction->payment_url = $paymentUrl;
        $transaction->price = $price;
        $transaction->redirect_url = $redirectUrl;
        $transaction->description = $description;

        $transaction->save();

        if ($transaction->price == 0) $transaction->capture();

        return $transaction;
    }

    public function countAll($isPaid = null)
    {
        $query = Transaction::query();
        if ($isPaid === true)
            $query->whereNotNull('paid_at');
        if ($isPaid === false)
            $query->whereNull('paid_at');
        return $query->count();
    }

}
