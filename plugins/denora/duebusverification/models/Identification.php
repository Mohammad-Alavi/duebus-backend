<?php namespace Denora\Duebusverification\Models;

use Model;

/**
 * Model
 */
class Identification extends Model {
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusverification_identifications';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $attachOne = [
        'image' => 'System\Models\File'
    ];

    public $morphOne = [
        'details' => ['Denora\Duebusverification\Models\Item', 'name' => 'itemable']
    ];

    public $belongsTo = [
        'investor_verification' => 'Denora\Duebusverification\Models\InvestorVerification'
    ];

}
