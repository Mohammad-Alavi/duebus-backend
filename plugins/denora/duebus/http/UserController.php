<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\UserTransformer;
use RainLab\User\Models\User;
use UserRepository;

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
