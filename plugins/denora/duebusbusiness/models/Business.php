<?php namespace Denora\Duebusbusiness\Models;

use Denora\TapCompany\Classes\Repositories\TransactionRepository;
use Model;

/**
 * Model
 */
class Business extends Model {

    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['year_founded', 'deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusbusiness_businesses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];

    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

    public function getIsPublishedAttribute(){
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->findBusinessTransaction($this->id);
        return $transaction != null;
    }

    public function getPaidAtAttribute(){
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->findBusinessTransaction($this->id);
        return $transaction?$transaction->paid_at:null;
    }

}
