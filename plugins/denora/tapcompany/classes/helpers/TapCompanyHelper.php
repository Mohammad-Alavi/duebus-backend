<?php namespace Denora\TapCompany\Classes\Helpers;

use Denora\TapCompany\Models\Settings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Psr\Http\Message\ResponseInterface;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

class TapCompanyHelper {

    /**
     * @param int $price
     *
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     */
    public function createCharge(int $price) {
        /** @var User $user */
        $user = Auth::user();
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
                'headers' => ['Authorization' => "Bearer " . self::getApiKey()],
                'json'    => $jsonArray
            ]
        );

        return $response;
    }

    /**
     * @return string
     */
    static public function getApiKey(): string {
        $testSecretApiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';

        return Settings::instance()->secret_api_key ?: $testSecretApiKey;
    }

}