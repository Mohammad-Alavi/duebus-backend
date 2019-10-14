<?php namespace Denora\Duebusverification\Models;

use Model;

/**
 * Model
 */
class Item extends Model {
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusverification_items';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $morphTo = [
        'itemable' => []
    ];

}
