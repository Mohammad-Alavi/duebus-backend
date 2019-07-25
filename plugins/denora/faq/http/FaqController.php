<?php namespace Denora\Faq\Http;

use Backend\Classes\Controller;

/**
 * Faq Controller Back-end Controller
 */
class FaqController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

}
