<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Response;
use RainLab\User\Facades\Auth;

/**
 * Entrepreneur Controller Back-end Controller
 */
class EntrepreneurController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store() {
        $entrepreneurRepository = new EntrepreneurRepository();

        if (Auth::user()->entrepreneur) return Response::make(['You already are an entrepreneur'], 400);

        $entrepreneur = $entrepreneurRepository->createEntrepreneur(Auth::user()->id);

        return ProfileTransformer::transform($entrepreneur->user);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id) {
        $entrepreneurRepository = new EntrepreneurRepository();
        $entrepreneur = $entrepreneurRepository->findById($id);
        if (!$entrepreneur) return Response::make(['No element found'], 404);

        if ($entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $entrepreneurRepository->deleteEntrepreneur($id);
    }

}
