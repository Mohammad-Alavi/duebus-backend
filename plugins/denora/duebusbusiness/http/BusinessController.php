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

        $validator = $this->getStoreValidator($data);

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
            $data['is_founder_or_holder_in_debt'],
            $this->generateThreeYearsStatement($data),
            $this->generateSocialMedia($data),
            $this->generateEquityHolders($data)
        );

        return BusinessTransformer::transform($business);
    }

    /*    public function update($id) {
            $businessRepository = new BusinessRepository();
            $business = $businessRepository->findById($id);
            if (!$business) return Response::make(['No element found'], 404);

            if ($business->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

            $data = Request::all();

            $validator = $this->getUpdateValidator($data);

            if ($validator->fails())
                return Response::make($validator->messages(), 400);

            $business = $businessRepository->updateBusiness($id, $data);

            return BusinessTransformer::transform($business);
        }*/

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


    /**
     * @param $data
     *
     * @return
     */
    private function getStoreValidator($data) {
        return Validator::make($data, [
            //  General data
            'logo'                                            => 'image|max:4096',  //  Size validator is in KB
            'name'                                            => 'min:3',
            'industry'                                        => 'min:3',
            'year_founded'                                    => 'numeric',
            'website'                                         => 'url',
            'allow_reveal'                                    => 'boolean',
            'existing_business'                               => 'boolean',
            'legal_structure'                                 => 'min:2',
            'your_role_in_business'                           => 'min:2',
            'reason_of_selling_equity'                        => 'min:2',
            'business_value'                                  => 'numeric',
            'equity_for_sale'                                 => 'numeric',
            'is_involved_in_any_proceedings'                  => 'boolean',
            'is_concern_with_business_employees'              => 'boolean',
            'is_founder_or_holder_in_debt'                    => 'boolean',

            //  3-Years Statement
            'latest_operating_performance.revenue'            => 'numeric',
            'latest_operating_performance.cost_of_goods_sold' => 'numeric',
            'latest_operating_performance.salaries'           => 'numeric',
            'latest_operating_performance.operating_expenses' => 'numeric',
            'latest_operating_performance.ebitda'             => 'numeric',
            'latest_operating_performance.ebit'               => 'numeric',
            'latest_operating_performance.net_profit'         => 'numeric',

            'assets.cash_and_equivalents' => 'numeric',
            'assets.accounts_receivable'  => 'numeric',
            'assets.inventory'            => 'numeric',
            'assets.tangible_assets'      => 'numeric',
            'assets.intangible_assets'    => 'numeric',
            'assets.financial_assets'     => 'numeric',

            'liabilities.accounts_payable'          => 'numeric',
            'liabilities.other_current_liabilities' => 'numeric',
            'liabilities.long_term_liabilities'     => 'numeric',
            'liabilities.equity'                    => 'numeric',

            //  Social Media
            'social_media.instagram'                => 'min:3',
            'social_media.facebook'                 => 'min:3',
            'social_media.linked_in'                => 'min:3',
            'social_media.youtube'                  => 'min:3',

            //  Equity Holders
            'equity_holders'                        => 'json',

        ]);
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function generateThreeYearsStatement($data): array {
        return [
            'latest_operating_performance' => [
                'revenue'            => array_has($data, 'latest_operating_performance.revenue') ? $data['latest_operating_performance']['revenue'] : null,
                'cost_of_goods_sold' => array_has($data, 'latest_operating_performance.cost_of_goods_sold') ? $data['latest_operating_performance']['cost_of_goods_sold'] : null,
                'salaries'           => array_has($data, 'latest_operating_performance.salaries') ? $data['latest_operating_performance']['salaries'] : null,
                'operating_expenses' => array_has($data, 'latest_operating_performance.operating_expenses') ? $data['latest_operating_performance']['operating_expenses'] : null,
                'ebitda'             => array_has($data, 'latest_operating_performance.ebitda') ? $data['latest_operating_performance']['ebitda'] : null,
                'ebit'               => array_has($data, 'latest_operating_performance.ebit') ? $data['latest_operating_performance']['ebit'] : null,
                'net_profit'         => array_has($data, 'latest_operating_performance.net_profit') ? $data['latest_operating_performance']['net_profit'] : null,
            ],
            'assets'                       => [
                'cash_and_equivalents' => array_has($data, 'assets.cash_and_equivalents') ? $data['assets']['cash_and_equivalents'] : null,
                'accounts_receivable'  => array_has($data, 'assets.accounts_receivable') ? $data['assets']['accounts_receivable'] : null,
                'inventory'            => array_has($data, 'assets.inventory') ? $data['assets']['inventory'] : null,
                'tangible_assets'      => array_has($data, 'assets.tangible_assets') ? $data['assets']['tangible_assets'] : null,
                'intangible_assets'    => array_has($data, 'assets.intangible_assets') ? $data['assets']['intangible_assets'] : null,
                'financial_assets'     => array_has($data, 'assets.financial_assets') ? $data['assets']['financial_assets'] : null,
            ],
            'liabilities'                  => [
                'accounts_payable'          => array_has($data, 'liabilities.accounts_payable') ? $data['liabilities']['accounts_payable'] : null,
                'other_current_liabilities' => array_has($data, 'liabilities.other_current_liabilities') ? $data['liabilities']['other_current_liabilities'] : null,
                'long_term_liabilities'     => array_has($data, 'liabilities.long_term_liabilities') ? $data['liabilities']['long_term_liabilities'] : null,
                'equity'                    => array_has($data, 'liabilities.equity') ? $data['liabilities']['equity'] : null,
            ],

        ];
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function generateSocialMedia($data): array {
        return [
            'instagram' => array_has($data, 'social_media.instagram') ? $data['social_media']['instagram'] : null,
            'facebook'  => array_has($data, 'social_media.facebook') ? $data['social_media']['facebook'] : null,
            'linked_in' => array_has($data, 'social_media.linked_in') ? $data['social_media']['linked_in'] : null,
            'youtube'   => array_has($data, 'social_media.youtube') ? $data['social_media']['youtube'] : null,
        ];
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function generateEquityHolders($data): array {
        $equityHolders = [];

        try {
            foreach (json_decode($data['equity_holders']) as $equityHolder) {
                array_push($equityHolders, [
                    'name'   => $equityHolder->name,
                    'equity' => $equityHolder->equity,
                    'email'  => $equityHolder->email,
                    'role'   => $equityHolder->role,

                ]);
            }
        } finally {
            return $equityHolders;
        }
    }
}
