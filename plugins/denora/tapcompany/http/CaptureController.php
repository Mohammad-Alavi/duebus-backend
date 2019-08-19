<?php namespace Denora\Tapcompany\Http;

use Backend\Classes\Controller;
use Denora\TapCompany\Classes\Repositories\TransactionRepository;
use Denora\TapCompany\Models\Settings;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use RainLab\User\Facades\Auth;

/**
 * Capture Controller Back-end Controller
 */
class CaptureController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    function index() {

        $chargeId = Input::get('tap_id');
//        $tapCompanySecretApiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';
        $tapCompanySecretApiKey = Settings::instance()->secret_api_key;
        $requestUrl = 'https://api.tap.company/v2/charges/';
        $client = new Client();
        $response = $client->request(
            'GET',
            $requestUrl . $chargeId,
            ['headers' => ['Authorization' => "Bearer {$tapCompanySecretApiKey}"],]
        );

        //  Save data
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->findByChargeId($chargeId);
        $body = $response->getBody()->getContents();
        $status = json_decode($body, true)['status'];
        if ($status == 'CAPTURED') {
            $transaction->capture();
            return Response::make(['Payment succeed'], 200);
        }else
            return Response::make(['Payment failed'], 400);
    }

}
