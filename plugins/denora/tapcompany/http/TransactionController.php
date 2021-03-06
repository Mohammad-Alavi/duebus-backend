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
        if ($validator->fails()) return Response::make($validator->messages(), 400);

        //  Validate wallet_payload json
        $hasWalletPayload = array_has($data, 'wallet_payload');
        if ($hasWalletPayload) {
            $walletPayload = $data['wallet_payload'];
            $walletPayloadValidation = $this->validateWalletPayloadJson($walletPayload);
            if ($walletPayloadValidation->fails()) return Response::make($walletPayloadValidation->messages(), 400);
        }

        //  Validate inquiry_payload json
        $hasInquiryPayload = array_has($data, 'inquiry_payload');
        if ($hasInquiryPayload) {
            $inquiryPayload = $data['inquiry_payload'];
            $inquiryPayloadValidation = $this->validateInquiryPayloadJson($inquiryPayload);
            if ($inquiryPayloadValidation->fails()) return Response::make($inquiryPayloadValidation->messages(), 400);
        }


        $helper = new TapCompanyHelper();

        $walletPayload = null;
        $inquiryPayload = null;
        $packageId = null;
        if ($data['chargeable'] == 'wallet') {
            $packageId = $data['package_id'];
            $walletPayload = Request::input('wallet_payload', null);
            $packageRepository = new PackageRepository();
            $package = $packageRepository->findById($packageId);
            $price = $package->price;
            $chargeableId = $user->id;
        } else if ($data['chargeable'] == 'business') {
            $price = ConfigTransformer::transform()['prices']['business_price_with_no_package'];
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not already paid
            if ($business->is_published) return Response::make(['The business has been already paid'], 409);
        } else if ($data['chargeable'] == 'view') {
            if (!$user->investor) return Response::make(['You must be an investor'], 400);
            $price = ConfigTransformer::transform()['prices']['view_price_with_no_package'];
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not already viewed
            BusinessRepository::removeExpiredViewed();
            $isOwned = $user->id == $business->entrepreneur->user->id;
            $isViewed = BusinessRepository::isBusinessViewed($user->investor, $business->id);
            $isViewable = $isOwned || $isViewed;
            if ($isViewable) return Response::make(['The business has been already viewed'], 409);
        } else if ($data['chargeable'] == 'reveal') {
            if (!$user->investor) return Response::make(['You must be an investor'], 400);
            $price = ConfigTransformer::transform()['prices']['reveal_price_with_no_package'];
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not already revealed
            $isOwned = $user->id == $business->entrepreneur->user->id;
            $isRevealed = $user->investor->revealed_businesses->contains($business->id);
            $isRevealable = $isOwned || $isRevealed;
            if ($isRevealable) return Response::make(['The business has been already revealed'], 409);
        } else if ($data['chargeable'] == 'inquiry') {
            if (!$user->investor) return Response::make(['You must be an investor'], 400);
            $inquiryPayload = $data['inquiry_payload'];
            $price = ConfigTransformer::transform()['prices']['inquiry_price_with_no_package'];
            $price = $price * count(json_decode($inquiryPayload));
            $chargeableId = $data['business_id'];
            $business = (new BusinessRepository())->findById($chargeableId);

            // check if the business is not owned
            $isOwned = $user->id == $business->entrepreneur->user->id;
            if ($isOwned) return Response::make(['You can not inquiry on your own business'], 409);
        } else {
            return Response::make(['Chargeable is not recognized'], 400);
        }

        if ($price == 0){
            $chargeId = null;
            $transactionUrl = $data['redirect_url'];
        }else{
            /** @var ResponseInterface $response */
            $response = $helper->createCharge($price);

            //  Create Transaction
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);
            $chargeId = $body['id'];
            $transactionUrl = $body['transaction']['url'];
        }

        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->createTransaction(
            $user->id,
            $packageId,
            $data['chargeable'],
            $chargeableId,
            $walletPayload,
            $inquiryPayload,
            $chargeId,
            $transactionUrl,
            $price,
            $data['redirect_url'],
            ''
        );

        $transaction = $transactionRepository->find($transaction->id);

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
                Rule::in(['wallet', 'business', 'view', 'reveal', 'inquiry']),
            ],
            "business_id" => 'required_if:chargeable,business|required_if:chargeable,view|required_if:chargeable,reveal|required_if:chargeable,inquiry',
            "wallet_payload" => 'json',
            "inquiry_payload" => 'required_if:chargeable,inquiry|json',
            "package_id" => 'required_if:chargeable,==,wallet',
            "redirect_url" => 'required',
        ]);
    }

    private function validateInquiryPayloadJson($json)
    {
        $data = ['data' => json_decode($json, true)];

        return Validator::make($data, [
                'data.*.title' => 'required|string',
                'data.*.text' => 'required|string',
            ]
        );
    }

    private function validateWalletPayloadJson($json)
    {
        $data = ['data' => json_decode($json, true)];

        return Validator::make($data, [
                'data.chargeable_type' => 'required|string',
                'data.chargeable_id' => 'required|int',
            ]
        );
    }


}
