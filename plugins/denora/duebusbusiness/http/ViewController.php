<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessesTransformer;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * View Controller Back-end Controller
 */
class ViewController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index(){
        $user = Auth::user();

        //  Stop user if it is not an investor
          if (!$user->investor) return Response::make(['You must be an investor'], 400);

        $page = Request::input('page', 1);

        $businesses = (new BusinessRepository())->paginateViewedBusinesses($user->investor, $page);

        return new LengthAwarePaginator(
            BusinessesTransformer::transform($businesses),
            $businesses->total(),
            $businesses->perPage()
        );

    }


    public function store()
    {
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'business_id' => 'required|integer',
        ]);
        if ($validator->fails()) return Response::make($validator->messages(), 400);

        //  Stop user if it is not an investor
        if (!$user->investor) return Response::make(['You must be an investor'], 400);

        $businessId = $data['business_id'];

        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($businessId);

        BusinessRepository::removeExpiredViewed();
        $isOwned = $user->id == $business->entrepreneur->user->id;
        $isViewed = BusinessRepository::isBusinessViewed($user->investor, $businessId);
        $isViewable = $isOwned || $isViewed;

        //  View it free
        if ($isViewable) return BusinessTransformer::transform($business);

        //  Buy it
        $price = ConfigTransformer::transform()['prices']['view_price_with_package'];
        if ($user->decreasePoints($price, 'view')){
            //  Add new relation
            $businessRepository->viewBusiness($user->investor, $businessId);
            //  Show the business
            $business = $businessRepository->findById($businessId);
            return BusinessTransformer::transform($business);
        }
        else
            return Response::make(['Not enough points'], 400);
    }

}
