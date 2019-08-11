<?php

namespace Denora\TapCompany\Components;

use Cms\Classes\CodeBase;
use Cms\Classes\ComponentBase;
use Denora\Letterwriting\Models\Order;
use Denora\Letterwriting\Models\OrderRepository;
use Denora\TapCompany\Models\Settings;
use Denora\TapCompany\Models\Transaction;
use Denora\TapCompany\Models\TransactionRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Input;
use RainLab\User\Facades\Auth;

class PaymentVerify extends ComponentBase {

    /**
     * @var Transaction
     */
    public $transaction;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(CodeBase $cmsObject = null, $properties = []) {
        parent::__construct($cmsObject, $properties);

        $this->transactionRepository = new TransactionRepository();
        $this->orderRepository = new OrderRepository();
    }

    public function init() {
        parent::init();
        $chargeId = Input::get('tap_id');

        $this->transaction = $this->transactionRepository->findByChargeId($chargeId);
        if ($this->transaction == null) dd('ERROR!');

        $this->order = $this->orderRepository->find($this->transaction->order_id);
        if ($this->order == null) dd('ERROR!');

        $this->verifyCharge($chargeId);
    }

    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails() {
        return [
            'name'        => 'Payment Verify',
            'description' => 'Verifies the payment comes back from tap.company'
        ];

    }

    /**
     * Defines the properties used by this class.
     * This method should be used as an override in the extended class.
     */
    public function defineProperties() {
        return [
            'charge_id' => [
                'title'       => 'Charge ID',
                'description' => 'ID of the charge comes back from tap.company',
                'type'        => 'string',
                'default'     => '0',
            ]
        ];
    }

    /**
     * @param string $chargeId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function verifyCharge(string $chargeId) {

//        $tapCompanySecretApiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';
        $tapCompanySecretApiKey = Settings::instance()->secret_api_key;
        $requestUrl = 'https://api.tap.company/v2/charges/';

        $client = new Client();
        $response = $client->request(
            'GET',
            $requestUrl . $chargeId,
            ['headers' => ['Authorization' => "Bearer {$tapCompanySecretApiKey}"],]
        );
        $this->onDone($response);
    }

    private function onDone(Response $response) {
        $body = $response->getBody()->getContents();
        $status = json_decode($body, true)['status'];
        $this->page['status'] = $status;
        if ($status == 'CAPTURED') {
            $this->transactionRepository->capture(
                Auth::user() ? Auth::user()->id : 0,
                $this->transaction->charge_id);
            $this->page['order_id'] = $this->transaction->order_id;
        }

    }

}
