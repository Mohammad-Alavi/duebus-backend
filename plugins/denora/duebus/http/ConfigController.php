<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebus\Classes\Transformers\CountryTransformer;
use Denora\Duebus\Models\Settings;

/**
 * Config Controller Back-end Controller
 */
class ConfigController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function show($section){
        if ($section == 'country')
            return CountryTransformer::transform();
    }

    public function index() {
        return ConfigTransformer::transform();
    }

}
