<?php namespace Denora\Tapcompany\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\TapCompany\Classes\Helpers\TapCompanyHelper;
use Denora\TapCompany\Classes\Repositories\TransactionRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;

/**
 * Capture Controller Back-end Controller
 */
class CaptureController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    function index()
    {

        $frontEndUrl = ConfigTransformer::transform()['front_end_url'];

        $chargeId = Input::get('tap_id');
        $requestUrl = 'https://api.tap.company/v2/charges/';
        $client = new Client();
        $response = $client->request(
            'GET',
            $requestUrl . $chargeId,
            ['headers' => ['Authorization' => "Bearer " . TapCompanyHelper::getApiKey()],]
        );

        //  Save data
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->findByChargeId($chargeId);
        $body = $response->getBody()->getContents();
        $status = json_decode($body, true)['status'];
        if ($status == 'CAPTURED') {
            $transaction->capture();
            $responseUrl = $frontEndUrl . '/payment-success';
        } else
            $responseUrl = $frontEndUrl . '/payment-error';
        header("Location: $responseUrl"); exit();
    }

}
