<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Repositories\UserRepository;
use Denora\Duebus\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

/**
 * Profile Controller Back-end Controller
 */
class ProfileController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';


    //  Get Profile
    public function index() {
        /** @var User $user */
        $user = Auth::user();

        return ProfileTransformer::transform($user);
    }

    //  Edit Profile
    public function store() {
        $data = Request::all();

        $validator = Validator::make($data, [
            'name'    => 'min:3',
            'surname' => 'min:3',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $userRepository = new UserRepository();
        $updatedUser = $userRepository->updateUser(Auth::user()->id, $data);

        return ProfileTransformer::transform($updatedUser);
    }

}
