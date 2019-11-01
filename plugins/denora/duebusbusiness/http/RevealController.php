<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Support\Facades\Response;
use RainLab\User\Facades\Auth;

/**
 * Reveal Controller Back-end Controller
 */
class RevealController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function show($businessId)
    {
        $user = Auth::user();
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($businessId);

        $isOwned = $user->id == $business->entrepreneur->user->id;
        $isRevealed = $user->investor->revealed_businesses->contains($businessId);
        $isRevealable = $isOwned || $isRevealed;

        //  View it free
        if ($isRevealable) return BusinessTransformer::transform($business);

        //  Buy it
        $price = ConfigTransformer::transform()['prices']['reveal_price'];
        if ($user->decreasePoints($price, 'reveal')){
            //  Add new relation
            $businessRepository->revealBusiness($user->investor, $businessId);
            //  Show the business
            $business = $businessRepository->findById($businessId);
            return BusinessTransformer::transform($business);
        }
        else
            return Response::make(['Not enough points'], 400);
    }


}
