<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;

/**
 * Sectors Back-end Controller
 */
class SectorController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

}
