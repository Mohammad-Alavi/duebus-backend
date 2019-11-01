<?php namespace Denora\Notification\Models;

use Model;

/**
 * Model
 */
class Notification extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_notification_notifications';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
