<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Denora\Duebusprofile\Classes\Repositories\InvestorRepository;
use Denora\Duebusprofile\Classes\Repositories\RepresentativeRepository;
use Event;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mail;
use RainLab\User\Models\Settings as RainLabUserSettings;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Http\Controllers\Traits\CanMakeUrl;
use RLuders\JWTAuth\Http\Controllers\Traits\CanSendMail;
use RLuders\JWTAuth\Http\Requests\RegisterRequest;
use RLuders\JWTAuth\Models\Settings;
use RLuders\JWTAuth\Models\User;

class RegisterController extends Controller {
    use CanMakeUrl,
        CanSendMail;

    /**
     * Register the user
     *
     * @param JWTAuth         $auth
     * @param RegisterRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function __invoke(
        JWTAuth $auth,
        RegisterRequest $request
    ) {
        if (!$this->canRegister()) {
            return response()->json(
                ['error' => 'registration_disabled'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $data = $request->all();

        //  region - Moslem added this
        $validator = Validator::make($data, [
            'type' => [
                'required',
                Rule::in(['investor', 'entrepreneur', 'representative']),
            ],
        ]);

        if ($validator->fails())
            return \Illuminate\Support\Facades\Response::make($validator->messages(), 400);
        //  endregion

        Event::fire('rainlab.user.beforeRegister', [&$data]);

        $activationMode = $this->getActivationMode();
        $user = $auth->register($data, ($activationMode == 'auto'));

        Event::fire('rainlab.user.register', [$user, $data]);

        if ($activationMode == 'email') {
            $this->sendActivationEmail($user);
        }

        // region - Moslem added this
        $this->setUserType($user, $data['type']);

        // endregion

        return response()->json([], Response::HTTP_CREATED);
    }

    //  region - Moslem added this
    private function setUserType($user, string $type) {
        switch ($type) {
            case 'investor':
                {
                    (new InvestorRepository)->createInvestor($user->id);
                    break;
                }
            case 'entrepreneur':
                {
                    (new EntrepreneurRepository())->createEntrepreneur($user->id);
                    break;
                }
            case 'representative':
                {
                    (new RepresentativeRepository())->createRepresentative($user->id);
                    (new InvestorRepository)->createInvestor($user->id);
                    (new EntrepreneurRepository())->createEntrepreneur($user->id);
                    break;
                }
        }
    }
    //  endregion

    /**
     * Check if the settings allow user registration
     *
     * @return boolean
     */
    protected function canRegister() {
        return RainLabUserSettings::get('allow_registration', true);
    }

    /**
     * Get the activation mode from configuration as string
     *
     * @return string
     */
    protected function getActivationMode() {
        switch (RainLabUserSettings::get('activate_mode')) {
            case RainLabUserSettings::ACTIVATE_USER:
                return 'email';
            case RainLabUserSettings::ACTIVATE_AUTO:
                return 'auto';
        }

        return 'manual';
    }

    /**
     * Sends the activation email to a user
     *
     * @param User $user
     *
     * @return void
     */
    protected function sendActivationEmail(User $user) {
        $code = implode('!', [$user->id, $user->getActivationCode()]);
        $link = $this->makeActivationUrl($code);

        $data = [
            'name' => $user->name,
            'link' => $link,
            'code' => $code
        ];

        $this->sendMail(
            $user->email,
            $user->name,
            'rainlab.user::mail.activate', $data
        );
    }

    /**
     * Returns a link used to activate the user account.
     *
     * @param string $code
     *
     * @return string
     */
    protected function makeActivationUrl($code) {
        $url = Settings::get('activation_url');

        return $this->makeUrl($url, $code);
    }
}
