<?php namespace Denora\Blog\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_blog_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'label' => 'required'
    ];
}
