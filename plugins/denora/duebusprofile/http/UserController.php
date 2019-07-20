<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Duebusprofile\Classes\Transformers\UserTransformer;

/**
 * User Controller Back-end Controller
 */
class UserController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function show($userId) {

        $userRepository = new UserRepository();
        $user = $userRepository->findById($userId);

        return UserTransformer::transform($user);
    }
}
