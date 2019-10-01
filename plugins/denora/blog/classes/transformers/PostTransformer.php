<?php namespace Denora\Blog\Classes\Transformers;

use Denora\Blog\Models\Post;

class PostTransformer {

    /**
     * @param Post $post
     *
     * @return array
     */
    static function transform($post) {
        return [
            'id'              => $post->id,
            'title'           => $post->title,
            'text'            => $post->title,
            'categories'      => $post->categories,
            'cover_thumbnail' => $post->cover ? $post->cover->getThumb(500, 0, ['mode' => 'auto']) : self::getDefaultImage(),
            'cover_original'  => $post->cover ? $post->cover->path : self::getDefaultImage(),

            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];

    }

    private static function getDefaultImage() {
        return 'http://cloudrangers.com/blog/wp-content/uploads/2017/08/blog_default.png';
    }
}