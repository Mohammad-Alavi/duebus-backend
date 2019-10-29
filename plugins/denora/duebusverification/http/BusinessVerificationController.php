<?php namespace Denora\Duebusverification\Http;

use Backend\Classes\Controller;

/**
 * Business Verification Controller Back-end Controller
 */
class BusinessVerificationController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

}
