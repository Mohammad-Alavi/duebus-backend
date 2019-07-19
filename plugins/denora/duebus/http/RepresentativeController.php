<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Repositories\RepresentativeRepository;
use Denora\Duebus\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Response;
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

        if (Auth::user()->representative) return Response::make(['You already are a representative'], 400);

        $representative = $representativeRepository->createRepresentative(Auth::user()->id);

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
        if (!$representative) return Response::make(['No element found'], 400);

        if ($representative->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $representativeRepository->deleteRepresentative($id);
    }


}
