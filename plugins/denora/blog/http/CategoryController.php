<?php namespace Denora\Blog\Http;

use Backend\Classes\Controller;

/**
 * Category Controller Back-end Controller
 */
class CategoryController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

}
