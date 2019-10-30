<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusbusiness\Classes\Repositories\AnalyzeEntrepreneurRepository;
use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Analyze Entrepreneur Controller Back-end Controller
 */
class AnalyzeEntrepreneurController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index(){
        $entrepreneur = Auth::user()->entrepreneur;

        if (!$entrepreneur) return Response::make(['You are not an entrepreneur'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        return AnalyzeEntrepreneurRepository::getAnalyzedData($entrepreneur);

    }
}
