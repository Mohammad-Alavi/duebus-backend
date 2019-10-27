<?php namespace Denora\Inbox\Models;

use Model;

/**
 * Model
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_inbox_messages';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = ['session' => 'Denora\Inbox\Models\Session'];
}
