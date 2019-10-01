<?php namespace Denora\Blog\Models;

use Model;

/**
 * Model
 */
class Post extends Model {
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_blog_posts';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
        'text'  => 'required',
    ];

    public $attachOne = [
        'cover' => 'System\Models\File',
    ];

    public $belongsToMany = [
        'categories' => [
            'Denora\Blog\Models\Category',
            'table' => 'denora_blog_posts_categories',
            'order' => 'label'
        ]
    ];

    /**
     * @param $query
     * @param $categories
     *
     * @return mixed
     */
    public function scopeFilterCategories($query, $categories) {
        return $query->whereHas('categories', function ($q) use ($categories) {
            $q->whereIn('id', $categories);
        });
    }


}
