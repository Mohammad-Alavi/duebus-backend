<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Hash;
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
        $user = Auth::user();

        $validator = Validator::make($data, [
            'name'                  => 'min:3',
            'surname'               => 'min:3',
            'current_password'      => '',
            'new_password'          => 'required_with:current_password|min:6',
            'password_confirmation' => 'required_with:new_password|same:new_password',
            'avatar'                => 'image'
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Check if current password is correct
        if (array_has($data, 'current_password') && !Hash::check($data['current_password'], $user->password))
            return Response::make(['Current password is wrong'], 400);

        $userRepository = new UserRepository();
        $updatedUser = $userRepository->updateUser($user->id, $data);

        return ProfileTransformer::transform($updatedUser);
    }

}
