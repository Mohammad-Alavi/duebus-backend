<?php namespace Denora\Duebus\Classes\Transformers;

use RainLab\Blog\Models\Post;

class PostsTransformer {

    /**
     * @param Post[] $posts
     *
     * @return array
     */
    static function transform($posts) {
        $array = [];

        if ($posts == null) return $array;

        foreach ($posts as $post) {
            array_push($array, PostTransformer::transform($post));
        }

        return $array;
    }

}