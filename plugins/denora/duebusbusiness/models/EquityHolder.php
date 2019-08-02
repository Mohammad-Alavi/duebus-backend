<?php namespace Denora\Duebusbusiness\Models;

use Model;

/**
 * Model
 */
class EquityHolder extends Model {

    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusbusiness_equityholder';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'business' => 'Denora\Duebusbusiness\Models\Business'
    ];

}
