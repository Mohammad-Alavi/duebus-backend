<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Business Controller Back-end Controller
 */
class BusinessController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function show($id) {
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($id);

        if (!$business) return Response::make(['No element found'], 404);

        return BusinessTransformer::transform($business);
    }

    public function store() {
        $user = Auth::user();
        $data = Request::all();

        $validator = Validator::make($data, [
            'logo'                               => 'image|max:4096',  //  Size validator is in KB
            'name'                               => 'required|min:3',
            'industry'                           => 'required|min:3',
            'year_founded'                       => 'required|numeric',
            'website'                            => 'url',
            'allow_reveal'                       => 'required|boolean',
            'existing_business'                  => 'required|boolean',
            'legal_structure'                    => 'required|min:2',
            'your_role_in_business'              => 'required|min:2',
            'reason_of_selling_equity'           => 'required|min:2',
            'business_value'                     => 'required|numeric',
            'equity_for_sale'                    => 'required|numeric',
            'is_involved_in_any_proceedings'     => 'required|boolean',
            'is_concern_with_business_employees' => 'required|boolean',
            'is_founder_or_holder_in_debt'       => 'required|boolean',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $businessRepository = new BusinessRepository();
        $business = $businessRepository->createBusiness(
            $user->id,
            array_has($data, 'logo') ? $data['logo'] : null,
            $data['name'],
            $data['industry'],
            $data['year_founded'],
            array_has($data, 'website') ? $data['website'] : null,
            $data['allow_reveal'],
            $data['existing_business'],
            $data['legal_structure'],
            $data['your_role_in_business'],
            $data['reason_of_selling_equity'],
            $data['business_value'],
            $data['equity_for_sale'],
            $data['is_involved_in_any_proceedings'],
            $data['is_concern_with_business_employees'],
            $data['is_founder_or_holder_in_debt']
        );

        return BusinessTransformer::transform($business);
    }

    public function update($id) {
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($id);
        if (!$business) return Response::make(['No element found'], 404);

        if ($business->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'logo'                               => 'image|max:4096',
            'name'                               => 'min:3',
            'industry'                           => 'min:3',
            'year_founded'                       => 'numeric',
            'website'                            => 'url',
            'allow_reveal'                       => 'boolean',
            'existing_business'                  => 'boolean',
            'legal_structure'                    => 'min:2',
            'your_role_in_business'              => 'min:2',
            'reason_of_selling_equity'           => 'min:2',
            'business_value'                     => 'numeric',
            'equity_for_sale'                    => 'numeric',
            'is_involved_in_any_proceedings'     => 'boolean',
            'is_concern_with_business_employees' => 'boolean',
            'is_founder_or_holder_in_debt'       => 'boolean',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $business = $businessRepository->updateBusiness($id, $data);

        return BusinessTransformer::transform($business);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id) {
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($id);
        if (!$business) return Response::make(['No element found'], 404);

        if ($business->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $businessRepository->deleteBusiness($id);
    }

}
