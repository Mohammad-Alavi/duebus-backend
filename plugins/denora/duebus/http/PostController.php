<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Repositories\PostRepository;
use Denora\Duebus\Classes\Transformers\PostsTransformer;
use Denora\Duebus\Classes\Transformers\PostTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Request;

/**
 * Blog Controller Back-end Controller
 */
class PostController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index() {
        $postRepository = new PostRepository();

        $page = Request::input('page', 1);

        $posts = $postRepository->paginate($page);

        return new LengthAwarePaginator(
            PostsTransformer::transform($posts),
            $posts->total(),
            $posts->perPage()
        );
    }

    public function show($postId) {
        $postRepository = new PostRepository();

        $post = $postRepository->findById($postId);

        if ($post)
            return PostTransformer::transform($post);
        else
            return response()->json(null, 404);

    }
}
