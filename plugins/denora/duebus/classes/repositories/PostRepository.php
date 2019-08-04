<?php namespace Denora\Duebus\Classes\Repositories;

use RainLab\Blog\Models\Post;

class PostRepository {

    /**
     * @param int $postId
     *
     * @return Post
     */
    public function findById(int $postId) {
        $post = new Post();
        $query = Post::query()->where('id', $postId);
        $query = $post->scopeIsPublished($query);

        return $query->first();
    }

    /**
     * @param int   $page
     *
     * @param array $categories
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $page, array $categories = null) {
        $post = new Post();
        $query = Post::query();
        $query = $post->scopeIsPublished($query);
        if ($categories) $query = $post->scopeFilterCategories($query, $categories);

        return $query->paginate(20, $page);
    }

}