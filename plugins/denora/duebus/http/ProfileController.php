<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ProfileTransformer;

/**
 * Profile Controller Back-end Controller
 */
class ProfileController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index() {
        return ProfileTransformer::transform();
    }

}
