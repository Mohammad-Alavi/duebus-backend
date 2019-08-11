<?php

namespace Denora\TapCompany\Components;

use Cms\Classes\CodeBase;
use Cms\Classes\ComponentBase;
use Denora\Letterwriting\Models\Order;
use Denora\Letterwriting\Models\OrderRepository;
use Denora\TapCompany\Models\Settings;
use Denora\TapCompany\Models\TransactionRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Pay extends ComponentBase {

    /**
     * @var Order
     */
    public $order;

    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    public function __construct(CodeBase $cmsObject = null, $properties = []) {
        parent::__construct($cmsObject, $properties);

        $this->transactionRepository = new TransactionRepository();
    }

    public function init() {
        parent::init();
        $orderId = $this->property('order_id');
        $orderRepository = new OrderRepository();
        $this->order = $orderRepository->find($orderId);

        $this->page['is_paid'] = $this->order->isPaid();

        if (!$this->order->isPaid())
            $this->createCharge();
    }

    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails() {
        return [
            'name'        => 'Pay',
            'description' => 'A form to pay an order'
        ];

    }

    /**
     * Defines the properties used by this class.
     * This method should be used as an override in the extended class.
     */
    public function defineProperties() {
        return [
            'order_id' => [
                'title'       => 'Order ID',
                'description' => 'ID of the order you are going to pay',
                'type'        => 'string',
                'default'     => '0',
            ]
        ];
    }

    private function createCharge() {

//        $tapCompanySecretApiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';
        $tapCompanySecretApiKey = Settings::instance()->secret_api_key;
        $requestUrl = 'https://api.tap.company/v2/charges';
        $redirectUrl = Config::get('app.url') . '/payment/payment-verify';
        $jsonArray = [
            'amount'   => $this->order->price,
            'currency' => 'KWD',
            'customer' => [
                'first_name' => $this->order->customer->name,
                'email'      => $this->order->customer->email,
                'phone'      => [
                    'country_code' => '965',
                    'number'       => '50000000'
                ]
            ],
            'source'   => [
                'id' => 'src_kw.knet'
            ],
            'redirect' => [
                'url' => $redirectUrl
            ]
        ];

        $client = new Client();
        $response = $client->request(
            'POST',
            $requestUrl,
            [
                'headers' => ['Authorization' => "Bearer {$tapCompanySecretApiKey}"],
                'json'    => $jsonArray
            ]
        );
        $this->onDone($response);
    }

    /**
     * @param Response $response
     */
    private function onDone(Response $response) {
        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);

        $chargeId = $body['id'];
        $transactionUrl = $body['transaction']['url'];

        $this->transactionRepository->create($this->order->id, $chargeId, $transactionUrl);


//        header('Location: ' . $transactionUrl);
//        exit();

        $this->page['transaction_url'] = $transactionUrl;
    }

}
