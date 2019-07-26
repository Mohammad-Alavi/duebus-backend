<?php namespace Denora\Duebus\Classes\Transformers;

use RainLab\Blog\Models\Post;

class PostTransformer {

    /**
     * @param Post $post
     *
     * @return array
     */
    static function transform($post) {

        return [
            'id'           => $post->id,
            'title'        => $post->title,
            'excerpt'      => $post->excerpt,
            'content_md'   => $post->content,
            'content_html' => $post->content_html,
            'published'    => $post->published,
            'published_at' => $post->published_at,
            'metadata'     => $post->metadata,
            'has_summary'  => $post->has_summary,
            'summary'      => $post->summary,
            'categories'   => $post->categories,

            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];

    }
}