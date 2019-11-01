<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Support\Facades\Response;
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

    public function show($businessId)
    {
        $user = Auth::user();
        $business = (new BusinessRepository())->findById($businessId);

        $isOwned = $user->id == $business->entrepreneur->user->id;
        $isViewed = $user->investor->viewed_businesses->contains($businessId);
        $isViewable = $isOwned || $isViewed;

        //  View it free
        if ($isViewable) return BusinessTransformer::transform($business);

        //  Buy it
        $price = ConfigTransformer::transform()['prices']['view_price'];
        if ($user->decreasePoints($price, 'view')){
            //  Add new relation
            $user->investor->viewed_businesses()->attach($businessId);
            //  Show the business
            return BusinessTransformer::transform($business);
        }
        else
            return Response::make(['Not enough points'], 400);
    }

}
