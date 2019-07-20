<?php namespace Denora\Duebusprofile\Models;

use Model;

/**
 * Model
 */
class Education extends Model {

    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebus_educations';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $dates = [
        'from',
        'to'
    ];

    public $belongsTo = [
        'entrepreneur' => 'Denora\Duebusprofile\Models\Entrepreneur'
    ];
}
