<?php namespace Denora\Duebus\Models;

use Model;

/**
 * Model
 */
class Sector extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebus_sectors';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'investors' => ['Denora\Duebus\Models\Investor', 'table' => 'denora_duebus_investor_sector']
    ];
}
