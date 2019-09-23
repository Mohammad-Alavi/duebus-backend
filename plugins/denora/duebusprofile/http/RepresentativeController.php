<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\RepresentativeRepository;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
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
            'number_of_clients' => 'required',
            'interested_in'     => 'required',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);


        $representative = $representativeRepository->createRepresentative(
            $user->id,
            $data['number_of_clients'],
            $data['interested_in']
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
            'number_of_clients'             => 'string',
            'interested_in' => 'string',
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
