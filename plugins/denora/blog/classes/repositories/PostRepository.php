<?php namespace Denora\Blog\Classes\Repositories;

use Denora\Blog\Models\Post;

class PostRepository {

    /**
     * @param int $postId
     *
     * @return Post
     */
    public function findById(int $postId) {
        return Post::find($postId);
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
        if ($categories) $query = $post->scopeFilterCategories($query, $categories);

        return $query->paginate(20, $page);
    }

}