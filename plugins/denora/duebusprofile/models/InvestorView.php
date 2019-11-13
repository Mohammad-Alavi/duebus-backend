<?php namespace Denora\Duebusprofile\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;

/**
 * Model
 */
class InvestorView extends Model {

    use SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebus_investor_view';

    protected $dates = ['deleted_at'];

}
