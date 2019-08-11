<?php namespace Denora\Duebus\Models;

use Model;

/**
 * Model
 */
class Package extends Model {
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebus_packages';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function getPriceAttribute() {
        return $this->amount - $this->offer;
    }

}
