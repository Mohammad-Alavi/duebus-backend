<?php namespace Denora\Tapcompany\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Repositories\PackageRepository;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\TapCompany\Classes\Helpers\TapCompanyHelper;
use Denora\TapCompany\Classes\Repositories\TransactionRepository;
use Denora\TapCompany\Classes\Transformers\TransactionTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Psr\Http\Message\ResponseInterface;
use RainLab\User\Facades\Auth;

/**
 * Transaction Controller Back-end Controller
 */
class TransactionController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    function store()
    {
        $user = Auth::user();
        $data = Request::all();

        $validator = $this->getStoreValidator($data);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $helper = new TapCompanyHelper();
        if ($data['chargeable'] == 'wallet') {
            $packageRepository = new PackageRepository();
            $package = $packageRepository->findById($data['package_id']);
            $price = $package->price;
            $points = $package->points;
            $chargeableId = $user->id;
        } else if ($data['chargeable'] == 'business') {
            $price = ConfigTransformer::transform()['prices']['business_price_with_no_package'];
            $points = 0;
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not already paid
            if ($business->is_published) return Response::make(['The business has been already paid'], 409);
        } else if ($data['chargeable'] == 'view') {
            if (!$user->investor) return Response::make(['You must be an investor'], 400);
            $price = ConfigTransformer::transform()['prices']['view_price_with_no_package'];
            $points = 0;
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not already viewed
            BusinessRepository::removeExpiredViewed();
            $isOwned = $user->id == $business->entrepreneur->user->id;
            $isViewed = $user->investor->viewed_businesses->contains($business->id);
            $isViewable = $isOwned || $isViewed;
            if ($isViewable) return Response::make(['The business has been already viewed'], 409);
        }else if ($data['chargeable'] == 'reveal') {
            if (!$user->investor) return Response::make(['You must be an investor'], 400);
            $price = ConfigTransformer::transform()['prices']['reveal_price_with_no_package'];
            $points = 0;
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not already revealed
            $isOwned = $user->id == $business->entrepreneur->user->id;
            $isRevealed = $user->investor->revealed_businesses->contains($business->id);
            $isRevealable = $isOwned || $isRevealed;
            if ($isRevealable) return Response::make(['The business has been already revealed'], 409);
        } else {
            return Response::make(['Chargeable is not recognized'], 400);
        }
        /** @var ResponseInterface $response */
        $response = $helper->createCharge($price);

        //  Create Transaction
        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $chargeId = $body['id'];
        $transactionUrl = $body['transaction']['url'];

        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->createTransaction(
            $user->id,
            $data['chargeable'],
            $chargeableId,
            $chargeId,
            $transactionUrl,
            $price,
            $points,
            $data['redirect_url'],
            ''
        );

        return TransactionTransformer::transform($transaction);

    }


    /**
     * @param $data
     *
     * @return
     */
    private function getStoreValidator($data)
    {
        return Validator::make($data, [
            //  General data
            'chargeable' => [
                'required',
                Rule::in(['wallet', 'business', 'view', 'reveal']),
            ],
            "business_id" => 'required_if:chargeable,business|required_if:chargeable,view|required_if:chargeable,reveal',
            "package_id" => 'required_if:chargeable,==,wallet',
            "redirect_url" => 'required',
        ]);
    }

}
