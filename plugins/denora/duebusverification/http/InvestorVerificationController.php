<?php namespace Denora\Duebusverification\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Denora\Duebusverification\Classes\Repositories\IdentificationRepository;
use Denora\Duebusverification\Classes\Repositories\PassportRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Investor Verification Controller Back-end Controller
 */
class InvestorVerificationController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store() {
        $user = Auth::user();

        if (!$user->investor) return Response::make(['You must be an investor'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'image'       => 'required|image',
            'type'        => [
                'required',
                Rule::in(['identification', 'passport']),
            ],
            'description' => 'string',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);


        switch ($data['type']){
            case 'identification': {
                //  Upload new identification data
                IdentificationRepository::updateIdentification($user->investor->verification, $data['image'], $data['description']);
                break;
            }
            case 'passport': {
                //  Upload new passport data
                PassportRepository::updatePassport($user->investor->verification, $data['image'], $data['description']);
                break;
            }
        }


        return ProfileTransformer::transform(Auth::user());
    }

}
