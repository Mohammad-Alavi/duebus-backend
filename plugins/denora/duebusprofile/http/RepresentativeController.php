<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Http\BusinessController;
use Denora\Duebusprofile\Classes\Repositories\RepresentativeRepository;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Representative Controller Back-end Controller
 */
class RepresentativeController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

/*    public function store() {
        $representativeRepository = new RepresentativeRepository();
        $user = Auth::user();

        if ($user->representative) return Response::make(['You already are a representative'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'number_of_clients'      => [
                'required',
                Rule::in(ConfigTransformer::transform()['registration_fields']['number_of_clients']),
            ],
            'interested_in'          => [
                'required',
                'json',
            ],
            'range_of_investment'    => [
                'required',
                Rule::in(ConfigTransformer::transform()['registration_fields']['range_of_investments']),
            ],
            'sectors'                => [
                'required'
            ],
            'business_name'          => 'required|min:3',
            'year_founded'           => 'required|date',
            'website'                => 'url',

            //  Social Media
            'social_media.instagram' => 'min:3',
            'social_media.facebook'  => 'min:3',
            'social_media.linked_in' => 'min:3',
            'social_media.youtube'   => 'min:3',

        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Validate interested_in json
        $hasInterestedIn = array_has($data, 'interested_in');
        if ($hasInterestedIn) {
            $interestedIn = $data['interested_in'];
            $interestedInValidation = $this->validateInterestedInJson($interestedIn);
            if ($interestedInValidation->fails()) return Response::make($interestedInValidation->messages(), 400);
        }

        $representative = $representativeRepository->createRepresentative(
            $user->id,
            Request::input('number_of_clients', null),
            Request::input('interested_in', '[]'),
            Request::input('range_of_investment', null),
            Request::input('sectors', '[]'),
            Request::input('business_name', null),
            Request::input('year_founded', null),
            Request::input('website', null),
            BusinessController::generateSocialMedia($data)
        );

        $user = (new UserRepository())->findById($representative->user->id);
        return ProfileTransformer::transform($user);
    }*/

    public function update($id) {
        $representativeRepository = new RepresentativeRepository();
        $representative = $representativeRepository->findById($id);
        if (!$representative) return Response::make(['No element found'], 404);

        if ($representative->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'number_of_clients'      => [
                Rule::in(ConfigTransformer::transform()['registration_fields']['number_of_clients']),
            ],
            'interested_in'          => [
                'json',
            ],
            'range_of_investment'    => [
                Rule::in(ConfigTransformer::transform()['registration_fields']['range_of_investments']),
            ],
            'business_name'          => 'min:3',
            'year_founded'           => 'date',
            'website'                => 'url',

            //  Social Media
            'social_media.instagram' => 'min:3',
            'social_media.facebook'  => 'min:3',
            'social_media.linked_in' => 'min:3',
            'social_media.youtube'   => 'min:3',

        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Validate interested_in json
        $hasInterestedIn = array_has($data, 'interested_in');
        if ($hasInterestedIn) {
            $interestedIn = $data['interested_in'];
            $interestedInValidation = $this->validateInterestedInJson($interestedIn);
            if ($interestedInValidation->fails()) return Response::make($interestedInValidation->messages(), 400);
        }

        $representative = $representativeRepository->updateRepresentative($id, $data);

        return ProfileTransformer::transform($representative->user);
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    private function validateInterestedInJson($json) {
        $data = ['data' => json_decode($json, true)];

        return Validator::make($data, [
            'data.*' => [
                'string',
                'required',
                Rule::in(ConfigTransformer::transform()['registration_fields']['interested_in']),
            ],
        ]);
    }

}
