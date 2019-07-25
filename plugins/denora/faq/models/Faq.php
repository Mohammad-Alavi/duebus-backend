<?php namespace Denora\Faq\Models;

use Model;

/**
 * Model
 */
class Faq extends Model {
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_faq_faqs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
