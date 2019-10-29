<?php namespace Denora\Duebusverification\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Model
 */
class CommercialLicense extends Model {
    use Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusverification_commercial_licenses';

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
        'entrepreneur_verification' => 'Denora\Duebusverification\Models\EntrepreneurVerification'
    ];

}
