<?php namespace Denora\Duebusbusiness\Models;

use Model;

/**
 * Model
 */
class Business extends Model {

    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusbusiness_businesses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasOne = [
        'socialMedia' => 'Denora\Duebusbusiness\Models\SocialMedia'
    ];

    public $hasMany = [
        'equityHolders' => 'Denora\Duebusbusiness\Models\EquityHolder'
    ];

}
