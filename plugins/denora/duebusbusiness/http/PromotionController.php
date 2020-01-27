<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Repositories\PromotionRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessesTransformer;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Promotion Repository Back-end Controller
 */
class PromotionController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index(){

        $validator = Validator::make(Request::all(), [
            'existing' => 'boolean',
        ]);

        if ($validator->fails()) return Response::make($validator->messages(), 400);


        $industry = Request::input('industry', null);
        $page = Request::input('page', 1);
        $existing = Request::input('existing', null);

        $businesses = PromotionRepository::paginate($page, $industry, $existing);

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
            'business_id' => 'required|integer',
            'industry' => 'string',
        ]);
        $industry = Request::input('industry', null);

        if ($validator->fails()) return Response::make($validator->messages(), 400);

        $existingPromotionsCount = PromotionRepository::getPromotionsCount($industry);
        $promotionsCountMax =  ConfigTransformer::transform()['promotions_count_max'];
        if ($existingPromotionsCount >= $promotionsCountMax) return Response::make(['No more empty slots for this industry'], 409);

        if (!$user->entrepreneur) return Response::make(['You are not an entrepreneur'], 400);

        $businessId = $data['business_id'];
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($businessId);
        if ($user->entrepreneur->id != $business->entrepreneur_id) return Response::make(['You must own the business'], 400);

        $price = $industry?
            ConfigTransformer::transform()['prices']['industry_promotion_price']:
            ConfigTransformer::transform()['prices']['duebus_promotion_price'];
        if ($user->decreasePoints($price, 'promote')){
            //  Promote the business
            PromotionRepository::promote($businessId, $industry);
            //  Show the business
            $business = $businessRepository->findById($businessId);
            return BusinessTransformer::transform($business);
        }
        else
            return Response::make(['Not enough points'], 400);

    }

    public function show($industry){
        if ($industry == 'duebus') $industry = null;
        $promotionsCountMax =  ConfigTransformer::transform()['promotions_count_max'];
        $count = PromotionRepository::getPromotionsCount($industry);
        $remaining = $promotionsCountMax - $count;

        return Response::make(['count' => $count, 'remaining' => $remaining], 200);
    }

}
