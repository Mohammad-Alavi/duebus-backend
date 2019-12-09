<?php namespace Denora\Duebusbusiness\Models;

use Denora\TapCompany\Classes\Repositories\TransactionRepository;
use Model;

/**
 * Model
 */
class Business extends Model {

    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['year_founded', 'deleted_at', 'promotion_expire_date', 'paid_at'];

    protected $casts = [
        'allow_reveal' => 'boolean',
        'existing_business' => 'boolean',
        'is_involved_in_any_proceedings' => 'boolean',
        'is_concern_with_business_employees' => 'boolean',
        'is_founder_or_holder_in_debt' => 'boolean',
        'is_published' => 'boolean',

        'business_value' => 'float',
        'equity_for_sale' => 'float',
        'asking_price' => 'float',
    ];

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
            'otherKey' => 'investor_id',
            'timestamps' => true,
            'softDelete' => true,
        ],
        'revealed_investors' => [
            'Denora\Duebusprofile\Models\Investor',
            'table' => 'denora_duebus_investor_reveal',
            'key'      => 'business_id',
            'otherKey' => 'investor_id',
            'timestamps' => true,
        ],
        'bookmarked_users' => [
            'RainLab\User\Models\User',
            'table' => 'denora_duebus_user_bookmark',
            'key'      => 'business_id',
            'otherKey' => 'user_id',
            'timestamps' => true,
        ],
    ];

    public $belongsTo = [
        'entrepreneur' => 'Denora\Duebusprofile\Models\Entrepreneur'
    ];

    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

}
