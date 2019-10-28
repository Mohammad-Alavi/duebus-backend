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
        $business = (new BusinessRepository())->findById($businessId);

        $isOwned = $user->id == $business->user_id;
        $isRevealed = $user->investor->revealed_businesses->contains($businessId);
        $isRevealable = $isOwned || $isRevealed;

        //  View it free
        if ($isRevealable) return BusinessTransformer::transform($business);

        //  Buy it
        $price = ConfigTransformer::transform()['prices']['reveal_price'];
        if ($user->decreasePoints($price, 'reveal')){
            //  Add new relation
            $user->investor->revealed_businesses()->attach($businessId);
            //  Show the business
            return BusinessTransformer::transform($business);
        }
        else
            return Response::make(['Not enough points'], 400);
    }


}
