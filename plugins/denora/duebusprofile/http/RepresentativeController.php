<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Http\BusinessController;
use Denora\Duebusprofile\Classes\Repositories\RepresentativeRepository;
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

    public function store() {
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
                Rule::in(ConfigTransformer::transform()['registration_fields']['interested_in']),
            ],
            'business_name'          => 'required|min:3',
            'year_founded'           => 'required|numeric',
            'website'                => 'required|url',

            //  Social Media
            'social_media.instagram' => 'min:3',
            'social_media.facebook'  => 'min:3',
            'social_media.linked_in' => 'min:3',
            'social_media.youtube'   => 'min:3',

        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);


        $representative = $representativeRepository->createRepresentative(
            $user->id,
            $data['number_of_clients'],
            $data['interested_in'],
            $data['business_name'],
            $data['year_founded'],
            $data['website'],
            BusinessController::generateSocialMedia($data)
        );

        return ProfileTransformer::transform($representative->user);
    }

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
                Rule::in(ConfigTransformer::transform()['registration_fields']['interested_in']),
            ],
            'business_name'          => 'min:3',
            'year_founded'           => 'numeric',
            'website'                => 'url',

            //  Social Media
            'social_media.instagram' => 'min:3',
            'social_media.facebook'  => 'min:3',
            'social_media.linked_in' => 'min:3',
            'social_media.youtube'   => 'min:3',

        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $representative = $representativeRepository->updateRepresentative($id, $data);

        return ProfileTransformer::transform($representative->user);
    }


    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id) {
        $representativeRepository = new RepresentativeRepository();
        $representative = $representativeRepository->findById($id);
        if (!$representative) return Response::make(['No element found'], 404);

        if ($representative->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $representativeRepository->deleteRepresentative($id);
    }


}
