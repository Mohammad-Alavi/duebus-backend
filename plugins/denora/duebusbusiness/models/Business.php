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

    public $belongsToMany = [
        'viewed_investors' => [
            'Denora\Duebusprofile\Models\Investor',
            'table' => 'denora_duebus_investor_view',
            'key'      => 'business_id',
            'otherKey' => 'investor_id'
        ],
        'revealed_investors' => [
            'Denora\Duebusprofile\Models\Investor',
            'table' => 'denora_duebus_investor_reveal',
            'key'      => 'business_id',
            'otherKey' => 'investor_id'
        ],
    ];

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];

    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

    public function getPaidAtAttribute(){
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->findBusinessTransaction($this->id);
        return $transaction?$transaction->paid_at:null;
    }

}
