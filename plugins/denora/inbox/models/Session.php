<?php namespace Denora\Inbox\Models;

use Model;

/**
 * Model
 */
class Session extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['preferred_date'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_inbox_sessions';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasMany = ['messages' => 'Denora\Inbox\Models\Message'];
}
