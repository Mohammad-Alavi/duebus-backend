<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Pay Business Controller Back-end Controller
 */
class PayBusinessController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store(){
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'business_id' => 'required|integer',
        ]);
        if ($validator->fails()) return Response::make($validator->messages(), 400);

        $businessId = $data['business_id'];

        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($businessId);

        //  Stop user if it is not an entrepreneur
        if (!$user->entrepreneur) return Response::make(['You must be an entrepreneur'], 400);

        //  Stop user if it does not own the business
        if ($user->entrepreneur->id != $business->entrepreneur_id) return Response::make(['You must own the business'], 400);


        //  View it free if it has been paid before
        if ($business->paid_at) return BusinessTransformer::transform($business);

        //  Buy it
        $price = ConfigTransformer::transform()['prices']['business_price_with_package'];
        if ($user->decreasePoints($price, 'pay business')){
            //  Pay business
            $businessRepository->payBusiness($businessId, $price);
            //  Show the business
            $business = $businessRepository->findById($businessId);
            return BusinessTransformer::transform($business);
        }
        else
            return Response::make(['Not enough points'], 400);

    }

}
