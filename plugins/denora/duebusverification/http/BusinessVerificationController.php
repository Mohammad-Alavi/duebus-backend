<?php namespace Denora\Duebusverification\Http;

use Backend\Classes\Controller;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Denora\Duebusverification\Classes\Repositories\AssociationArticleRepository;
use Denora\Duebusverification\Classes\Repositories\ChamberOfCommerceRepository;
use Denora\Duebusverification\Classes\Repositories\CommercialLicenseRepository;
use Denora\Duebusverification\Classes\Repositories\ManagingPartnerIDRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Business Verification Controller Back-end Controller
 */
class BusinessVerificationController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store()
    {
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'business_id' => 'required|integer',
            'image' => 'required|image',
            'type' => [
                'required',
                Rule::in(['id_of_managing_partner', 'article_of_association', 'commercial_license', 'chamber_of_commerce']),
            ],
            'description' => 'string',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $business = (new BusinessRepository)->findById($data['business_id']);
        if (!$business)
            return Response::make(['No element found'], 404);
        if ($business->entrepreneur_id !== $user->entrepreneur->id)
            return Response::make(['You must be the owner of the business'], 400);

        switch ($data['type']) {
            case 'id_of_managing_partner':
                {
                    //  Upload new id_of_managing_partner data
                    ManagingPartnerIDRepository::updateManagingPartnerID($business->verification, $data['image'], $data['description']);
                    break;
                }
            case 'article_of_association':
                {
                    //  Upload new article_of_association data
                    AssociationArticleRepository::updateAssociationArticle($business->verification, $data['image'], $data['description']);
                    break;
                }
            case 'commercial_license':
                {
                    //  Upload new commercial_license data
                    CommercialLicenseRepository::updateCommercialLicense($business->verification, $data['image'], $data['description']);
                    break;
                }
            case 'chamber_of_commerce':
                {
                    //  Upload new chamber_of_commerce data
                    ChamberOfCommerceRepository::updateChamberOfCommerce($business->verification, $data['image'], $data['description']);
                    break;
                }
        }

        return ProfileTransformer::transform(Auth::user());
    }


}
