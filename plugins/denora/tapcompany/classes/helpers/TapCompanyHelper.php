<?php namespace Denora\TapCompany\Classes\Helpers;

use Denora\TapCompany\Models\Settings;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

class TapCompanyHelper {

    /**
     * @param int $price
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createCharge(int $price) {

        /** @var User $user */
        $user = Auth::user();
//        $tapCompanySecretApiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';
        $tapCompanySecretApiKey = Settings::instance()->secret_api_key;
        $requestUrl = 'https://api.tap.company/v2/charges';
        $redirectUrl = Config::get('app.url') . '/api/v1/capture';
        $jsonArray = [
            'amount'   => $price,
            'currency' => 'KWD',
            'customer' => [
                'first_name' => $user->name,
                'email'      => $user->email,
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
        return $response;
    }

}