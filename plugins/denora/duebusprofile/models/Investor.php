<?php namespace Denora\Duebusprofile\Models;

use Model;

/**
 * Model
 */
class Investor extends Model {

    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebus_investors';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'sectors' => [
            'Denora\Duebus\Models\Sector',
            'table' => 'denora_duebus_investor_sector'
        ],
        'viewed_businesses' => [
            'Denora\Duebusbusiness\Models\Business',
            'table' => 'denora_duebus_investor_view',
            'key'      => 'investor_id',
            'otherKey' => 'business_id',
            'timestamps' => true,
            'softDelete' => true
        ],
        'revealed_businesses' => [
            'Denora\Duebusbusiness\Models\Business',
            'table' => 'denora_duebus_investor_reveal',
            'key'      => 'investor_id',
            'otherKey' => 'business_id',
            'timestamps' => true,
        ],
    ];

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];

    public $hasOne = [
        'verification' => ['Denora\Duebusverification\Models\InvestorVerification',]
    ];
}
