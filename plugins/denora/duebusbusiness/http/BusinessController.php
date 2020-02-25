<?php namespace Denora\Duebusbusiness\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Classes\Transformers\BusinessesTransformer;
use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Denora\Duebusprofile\Classes\Repositories\RepresentativeRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Business Controller Back-end Controller
 */
class BusinessController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index(){

        $user = Auth::user();

        //  Stop user if it is not an investor
        //  if (!$user->investor) return Response::make(['You must be an investor'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'page' => 'integer',
            'representative_id' => 'integer',
            'entrepreneur_id' => 'integer',
            'industry' => 'json',
            'revenue_from' => 'numeric',
            'revenue_to' => 'numeric',
            'sponsor' => 'string',
            'year_founded' => 'integer',
            'legal_structure' => 'json',
            'allow_reveal' => 'boolean',
            'existing_business' => 'boolean',
        ]);
        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $representativeId = Request::input('representative_id', null);
        if ($representativeId && !(new RepresentativeRepository())->findById($representativeId))
            return Response::make(['No such representative'], 400);

        $entrepreneurId = Request::input('entrepreneur_id', null);
        if ($entrepreneurId && !(new EntrepreneurRepository())->findById($entrepreneurId))
            return Response::make(['No such entrepreneur'], 400);

        $businessRepository = new BusinessRepository();
        $businesses = $businessRepository->paginate(
            Request::input('page', 1),
            $representativeId,
            $entrepreneurId,
            Request::input('industry', null),
            Request::input('revenue_from', null),
            Request::input('revenue_to', null),
            Request::input('sponsor', null),
            Request::input('year_founded', null),
            Request::input('legal_structure', null),
            Request::input('allow_reveal', null),
            Request::input('existing_business', null)
        );

        return new LengthAwarePaginator(
            BusinessesTransformer::transform($businesses),
            $businesses->total(),
            $businesses->perPage()
        );


    }

    public function show($id)
    {
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($id);

        if (!$business) return Response::make(['No element found'], 404);

        return BusinessTransformer::transform($business);
    }

    public function store()
    {
        $user = Auth::user();

        if (!$user->entrepreneur)
            return Response::make(['You are not an entrepreneur'], 400);

        $data = Request::all();
        $validator = $this->getStoreValidator($data);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Validate equity_holders json
        $hasEquityHolders = array_has($data, 'equity_holders');
        if ($hasEquityHolders) {
            $equityHolders = $data['equity_holders'];
            $equityHoldersValidation = $this->validateEquityHoldersJson($equityHolders);
            if ($equityHoldersValidation->fails()) return Response::make($equityHoldersValidation->messages(), 400);
        }

        $businessRepository = new BusinessRepository();
        $business = $businessRepository->createBusiness(
            $user->entrepreneur->id,
            array_has($data, 'logo') ? $data['logo'] : null,
            $data['name'],
            $data['business_brief'],
            $data['industry'],
            $data['year_founded'],
            Request::input('website', null),
            $data['allow_reveal'],
            $data['existing_business'],
            Request::input('legal_structure', null),
            $data['your_role_in_business'],
            $data['reason_of_selling_equity'],
            $data['business_value'],
            $data['equity_for_sale'],
            $data['asking_price'],
            Request::input('is_involved_in_any_proceedings', null),
            Request::input('is_involved_in_any_proceedings_description', null),
            Request::input('is_concern_with_business_employees', null),
            Request::input('is_concern_with_business_employees_description', null),
            Request::input('is_founder_or_holder_in_debt', null),
            Request::input('is_founder_or_holder_in_debt_description', null),
            $this->generateThreeYearsStatement($data),
            $this->generateSocialMedia($data),
            $this->generateEquityHolders($data)
        );

        return BusinessTransformer::transform($business);
    }

    public function update($id)
    {
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($id);

        if (!$business)
            return Response::make(['No element found'], 404);

        if ($business->entrepreneur->user->id != Auth::user()->id)
            return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = $this->getUpdateValidator($data);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Validate equity_holders json
        $hasEquityHolders = array_has($data, 'equity_holders');
        if ($hasEquityHolders) {
            $equityHolders = $data['equity_holders'];
            $equityHoldersValidation = $this->validateEquityHoldersJson($equityHolders);
            if ($equityHoldersValidation->fails()) return Response::make($equityHoldersValidation->messages(), 400);
        }

        $business = $businessRepository->updateBusiness($id, $data);

        return BusinessTransformer::transform($business);
    }

    /**
     * @param $data
     *
     * @return
     */
    private function getStoreValidator($data)
    {
        return Validator::make($data, [
            //  General data
            'logo' => 'nullable|image|max:4096',  //  Size validator is in KB
            'name' => 'required|min:3',
            'business_brief' => 'required|min:3',
            'industry' => [
                'required',
                Rule::in(array_column(ConfigTransformer::transform()['business_fields']['industries'], 'label'))
            ],
            'year_founded' => 'required|date',
            'website' => 'nullable|url',
            'allow_reveal' => 'required|boolean',
            'existing_business' => 'required|boolean',
            'legal_structure' => [
                'required_if:existing_business,==,1',
                Rule::in(ConfigTransformer::transform()['business_fields']['legal_structures'])
            ],
            'your_role_in_business' => [
                'required',
                Rule::in(ConfigTransformer::transform()['business_fields']['roles'])
            ],
            'reason_of_selling_equity' => [
                'required',
                Rule::in(ConfigTransformer::transform()['business_fields']['reasons_of_selling_equity'])
            ],
            'business_value' => 'required|numeric',
            'equity_for_sale' => 'required|numeric',
            'asking_price' => 'required|numeric',
            'is_involved_in_any_proceedings' => 'nullable|boolean',
            'is_involved_in_any_proceedings_description' => 'nullable|string',
            'is_concern_with_business_employees' => 'nullable|boolean',
            'is_concern_with_business_employees_description' => 'nullable|string',
            'is_founder_or_holder_in_debt' => 'nullable|boolean',
            'is_founder_or_holder_in_debt_description' => 'nullable|string',

            //  3-Years Statement
            'latest_operating_performance.revenue' => 'required|numeric',
            'latest_operating_performance.cost_of_goods_sold' => 'nullable|numeric',
            'latest_operating_performance.salaries' => 'nullable|numeric',
            'latest_operating_performance.operating_expenses' => 'nullable|numeric',
            'latest_operating_performance.ebitda' => 'nullable|numeric',
            'latest_operating_performance.ebit' => 'nullable|numeric',
            'latest_operating_performance.net_profit' => 'nullable|numeric',

            'assets.cash_and_equivalents' => 'nullable|numeric',
            'assets.accounts_receivable' => 'nullable|numeric',
            'assets.inventory' => 'nullable|numeric',
            'assets.tangible_assets' => 'nullable|numeric',
            'assets.intangible_assets' => 'nullable|numeric',
            'assets.financial_assets' => 'nullable|numeric',

            'liabilities.accounts_payable' => 'nullable|numeric',
            'liabilities.other_current_liabilities' => 'nullable|numeric',
            'liabilities.long_term_liabilities' => 'nullable|numeric',
            'liabilities.equity' => 'nullable|numeric',

            //  Social Media
            'social_media.instagram' => 'nullable|min:3',
            'social_media.facebook' => 'nullable|min:3',
            'social_media.linked_in' => 'nullable|min:3',
            'social_media.youtube' => 'nullable|min:3',

            //  Equity Holders
            'equity_holders' => 'nullable|json',

        ]);
    }

    /**
     * @param $data
     *
     * @return
     */
    private function getUpdateValidator($data)
    {
        return Validator::make($data, [
            //  General data
            'logo' => 'nullable|image|max:4096',  //  Size validator is in KB
            'name' => 'min:3',
            'business_brief' => 'min:3',
            'industry' => [
                Rule::in(array_column(ConfigTransformer::transform()['business_fields']['industries'], 'label'))
            ],
            'year_founded' => 'date',
            'website' => 'nullable|url',
            'allow_reveal' => 'boolean',
            'existing_business' => 'boolean',
            'legal_structure' => [
                Rule::in(ConfigTransformer::transform()['business_fields']['legal_structures'])
            ],
            'your_role_in_business' => [
                Rule::in(ConfigTransformer::transform()['business_fields']['roles'])
            ],
            'reason_of_selling_equity' => [
                Rule::in(ConfigTransformer::transform()['business_fields']['reasons_of_selling_equity'])
            ],
            'business_value' => 'numeric',
            'equity_for_sale' => 'numeric',
            'asking_price' => 'numeric',
            'is_involved_in_any_proceedings' => 'nullable|boolean',
            'is_involved_in_any_proceedings_description' => 'nullable|string',
            'is_concern_with_business_employees' => 'nullable|boolean',
            'is_concern_with_business_employees_description' => 'nullable|string',
            'is_founder_or_holder_in_debt' => 'nullable|boolean',
            'is_founder_or_holder_in_debt_description' => 'nullable|string',

            //  3-Years Statement
            'latest_operating_performance.revenue' => 'numeric',
            'latest_operating_performance.cost_of_goods_sold' => 'nullable|numeric',
            'latest_operating_performance.salaries' => 'nullable|numeric',
            'latest_operating_performance.operating_expenses' => 'nullable|numeric',
            'latest_operating_performance.ebitda' => 'nullable|numeric',
            'latest_operating_performance.ebit' => 'nullable|numeric',
            'latest_operating_performance.net_profit' => 'nullable|numeric',

            'assets.cash_and_equivalents' => 'nullable|numeric',
            'assets.accounts_receivable' => 'nullable|numeric',
            'assets.inventory' => 'nullable|numeric',
            'assets.tangible_assets' => 'nullable|numeric',
            'assets.intangible_assets' => 'nullable|numeric',
            'assets.financial_assets' => 'nullable|numeric',

            'liabilities.accounts_payable' => 'nullable|numeric',
            'liabilities.other_current_liabilities' => 'nullable|numeric',
            'liabilities.long_term_liabilities' => 'nullable|numeric',
            'liabilities.equity' => 'nullable|numeric',

            //  Social Media
            'social_media.instagram' => 'nullable|min:3',
            'social_media.facebook' => 'nullable|min:3',
            'social_media.linked_in' => 'nullable|min:3',
            'social_media.youtube' => 'nullable|min:3',

            //  Equity Holders
            'equity_holders' => 'nullable|json',

        ]);
    }

    private function validateEquityHoldersJson($json)
    {
        $data = ['data' => json_decode($json, true)];

        return Validator::make($data, [
            'data.*.name' => 'required|string|min:2',
            'data.*.mobile' => 'nullable',
            'data.*.equity' => 'required|numeric',
            'data.*.role' => [
                'nullable',
                Rule::in(ConfigTransformer::transform()['business_fields']['roles'])],
        ]);
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function generateThreeYearsStatement($data): array
    {
        return [
            'latest_operating_performance' => [
                'revenue' => array_has($data, 'latest_operating_performance.revenue') ? (int)$data['latest_operating_performance']['revenue'] : null,
                'cost_of_goods_sold' => array_has($data, 'latest_operating_performance.cost_of_goods_sold') ? (int)$data['latest_operating_performance']['cost_of_goods_sold'] : null,
                'salaries' => array_has($data, 'latest_operating_performance.salaries') ? (int)$data['latest_operating_performance']['salaries'] : null,
                'operating_expenses' => array_has($data, 'latest_operating_performance.operating_expenses') ?(int) $data['latest_operating_performance']['operating_expenses'] : null,
                'ebitda' => array_has($data, 'latest_operating_performance.ebitda') ? (int)$data['latest_operating_performance']['ebitda'] : null,
                'ebit' => array_has($data, 'latest_operating_performance.ebit') ? (int)$data['latest_operating_performance']['ebit'] : null,
                'net_profit' => array_has($data, 'latest_operating_performance.net_profit') ? (int)$data['latest_operating_performance']['net_profit'] : null,
            ],
            'assets' => [
                'cash_and_equivalents' => array_has($data, 'assets.cash_and_equivalents') ? (int)$data['assets']['cash_and_equivalents'] : null,
                'accounts_receivable' => array_has($data, 'assets.accounts_receivable') ? (int)$data['assets']['accounts_receivable'] : null,
                'inventory' => array_has($data, 'assets.inventory') ? (int)$data['assets']['inventory'] : null,
                'tangible_assets' => array_has($data, 'assets.tangible_assets') ? (int)$data['assets']['tangible_assets'] : null,
                'intangible_assets' => array_has($data, 'assets.intangible_assets') ? (int)$data['assets']['intangible_assets'] : null,
                'financial_assets' => array_has($data, 'assets.financial_assets') ? (int)$data['assets']['financial_assets'] : null,
            ],
            'liabilities' => [
                'accounts_payable' => array_has($data, 'liabilities.accounts_payable') ? (int)$data['liabilities']['accounts_payable'] : null,
                'other_current_liabilities' => array_has($data, 'liabilities.other_current_liabilities') ? (int)$data['liabilities']['other_current_liabilities'] : null,
                'long_term_liabilities' => array_has($data, 'liabilities.long_term_liabilities') ? (int)$data['liabilities']['long_term_liabilities'] : null,
                'equity' => array_has($data, 'liabilities.equity') ? (int)$data['liabilities']['equity'] : null,
            ],

        ];
    }

    /**
     * @param $data
     *
     * @return array
     */
    public static function generateSocialMedia($data): array
    {
        return [
            'instagram' => array_has($data, 'social_media.instagram') ? $data['social_media']['instagram'] : null,
            'facebook' => array_has($data, 'social_media.facebook') ? $data['social_media']['facebook'] : null,
            'linked_in' => array_has($data, 'social_media.linked_in') ? $data['social_media']['linked_in'] : null,
            'youtube' => array_has($data, 'social_media.youtube') ? $data['social_media']['youtube'] : null,
        ];
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function generateEquityHolders($data): array
    {
        $equityHolders = [];

        try {
            foreach (json_decode($data['equity_holders']) as $equityHolder) {
                array_push($equityHolders, [
                    'name' => $equityHolder->name,
                    'equity' => $equityHolder->equity,
                    'mobile' => $equityHolder->mobile,
                    'role' => $equityHolder->role,
                ]);
            }
        } finally {
            return $equityHolders;
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        $businessRepository = new BusinessRepository();
        $business = $businessRepository->findById($id);
        if (!$business) return Response::make(['No element found'], 404);

        if ($business->entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $businessRepository->deleteBusiness($id);
    }

    public function myBusinesses()
    {
        $user = Auth::user();

        if (!$user->entrepreneur)
            return Response::make(['You are not an entrepreneur'], 400);

        $businessRepository = new BusinessRepository();
        $businesses = $businessRepository->paginate(
            Request::input('page', 1),
            null,
            $user->entrepreneur->id,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            10000
        );

        return new LengthAwarePaginator(
            BusinessesTransformer::transform($businesses),
            $businesses->total(),
            $businesses->perPage()
        );


    }
}
