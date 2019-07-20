<?php namespace Denora\Duebusprofile\Models;

use Model;

/**
 * Model
 */
class Entrepreneur extends Model {

    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebus_entrepreneur';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasMany = [
        'experiences' => ['Denora\Duebusprofile\Models\Experience'],
        'educations'  => ['Denora\Duebusprofile\Models\Education'],
    ];

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];
}
