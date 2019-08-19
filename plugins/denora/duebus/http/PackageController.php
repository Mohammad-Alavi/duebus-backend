<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Repositories\PackageRepository;
use Denora\Duebus\Classes\Transformers\PackagesTransformer;
use Denora\Duebus\Classes\Transformers\PackageTransformer;

/**
 * Package Controller Back-end Controller
 */
class PackageController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index() {
        $packageRepository = new PackageRepository();

        $packages = $packageRepository->findAll();

        return PackagesTransformer::transform($packages);
    }

    public function show($packageId) {
        $packageRepository = new PackageRepository();

        $package = $packageRepository->findById($packageId);

        if ($package)
            return PackageTransformer::transform($package);
        else
            return response()->json(null, 404);

    }

}
