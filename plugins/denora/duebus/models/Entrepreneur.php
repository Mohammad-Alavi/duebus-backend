<?php namespace Denora\Duebus\Models;

use Model;

/**
 * Model
 */
class Entrepreneur extends Model
{
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
}
