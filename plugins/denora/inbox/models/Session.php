<?php namespace Denora\Inbox\Models;

use Model;

/**
 * Model
 */
class Session extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_inbox_sessions';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
