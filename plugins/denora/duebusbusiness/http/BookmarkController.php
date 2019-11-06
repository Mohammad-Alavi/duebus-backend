<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessesTransformer;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Bookmark Controller Back-end Controller
 */
class BookmarkController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index(){
        $user = Auth::user();

        $page = Request::input('page', 1);

        $businesses = $user->bookmarked_businesses()->paginate(10, $page);

        return new LengthAwarePaginator(
            BusinessesTransformer::transform($businesses),
            $businesses->total(),
            $businesses->perPage()
        );

    }

    public function store(){
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'business_id'  => 'required|integer',
            'bookmark_status'  => 'required|boolean',
        ]);

        $businessId = $data['business_id'];
        $bookmarkStatus = $data['bookmark_status'];

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        if ($bookmarkStatus)
            $user->bookmarked_businesses()->syncWithoutDetaching([$businessId]);
        else
            $user->bookmarked_businesses()->detach($businessId);

        $business = (new BusinessRepository())->findById($businessId);
        return BusinessTransformer::transform($business);
    }

}
