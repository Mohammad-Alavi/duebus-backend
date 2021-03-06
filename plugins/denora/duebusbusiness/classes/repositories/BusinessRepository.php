<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Denora\Duebusbusiness\Models\Business;
use Denora\Duebusprofile\Classes\Repositories\RepresentativeRepository;
use Denora\Duebusprofile\Models\InvestorView;
use Denora\Duebusverification\Classes\Repositories\BusinessVerificationRepository;
use Denora\Notification\Classes\Events\BusinessCreatedEvent;
use Denora\Notification\Classes\Events\BusinessPublishedEvent;
use Denora\Notification\Classes\Events\BusinessRevealedEvent;
use Denora\Notification\Classes\Events\BusinessViewedEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class BusinessRepository
{

    public static function isBusinessViewed($investor, int $businessId)
    {
        self::removeExpiredViewed();

        $query = InvestorView::query();
        $query->where('investor_id', $investor->id);
        $query->where('business_id', $businessId);
        //$query->whereDate('created_at', '>', Carbon::now()->subHours(2));

        return $query->count() > 0;

    }

    static public function removeExpiredViewed()
    {
        InvestorView::query()
            ->where('created_at', '<=', Carbon::now()->subHours(2))
            ->delete();
    }

    public static function getPercentCompletion(Business $business): int
    {
        $sum = 0;
        $fields = [
            'name',
            'industry',
            'year_founded',
            'website',
            'allow_reveal',
            'existing_business',
            'legal_structure',
            'your_role_in_business',
            'reason_of_selling_equity',
            'business_value',
            'equity_for_sale',
            'asking_price',
            'is_involved_in_any_proceedings',
            'is_concern_with_business_employees',
            'is_founder_or_holder_in_debt',
        ];
        foreach ($fields as $field) if ($business->$field !== null) $sum++;
        /*--*/
        $threeYearsStatement = json_decode($business->three_years_statement, true);
        $values = array_merge(
            $threeYearsStatement['latest_operating_performance'],
            $threeYearsStatement['assets'],
            $threeYearsStatement['liabilities']
        );
        foreach ($values as $value) if ($value !== null) $sum++;
        /*--*/

        return $sum / (count($fields) + count($values)) * 100;
    }

    /**
     * @param int $entrepreneurId
     * @param             $logo
     * @param string $name
     *
     * @param string $businessBrief
     * @param string $industry
     * @param int $yearFounded
     * @param string|null $website
     * @param bool $allowReveal
     * @param bool $existingBusiness
     * @param bool $hasCommercialLicense
     * @param $jurisdictionOfCommercialLicense
     * @param string $legalStructure
     * @param string $yourRoleInBusiness
     * @param string $reasonOfSellingEquity
     * @param float $businessValue
     * @param float $equityForSale
     * @param float $askingPrice
     * @param bool $isInvolvedInAnyProceedings
     * @param $isInvolvedInAnyProceedingsDescription
     * @param bool $isConcernWithBusinessEmployees
     * @param $isConcernWithBusinessEmployeesDescription
     * @param bool $isFounderOrHolderInDebt
     *
     * @param $isFounderOrHolderInDebtDescription
     * @param array $threeYearsStatement
     *
     * @param array $socialMedia
     *
     * @param array $equityHolders
     *
     * @param bool $isPublished
     * @return Business
     */
    public function createBusiness(
        int $entrepreneurId,
        $logo,
        string $name,
        string $businessBrief,
        string $industry,
        $yearFounded,
        $website,
        bool $allowReveal,
        bool $existingBusiness,
        bool $hasCommercialLicense,
        $jurisdictionOfCommercialLicense,
        $legalStructure,
        string $yourRoleInBusiness,
        string $reasonOfSellingEquity,
        float $businessValue,
        float $equityForSale,
        float $askingPrice,
        $isInvolvedInAnyProceedings,
        $isInvolvedInAnyProceedingsDescription,
        $isConcernWithBusinessEmployees,
        $isConcernWithBusinessEmployeesDescription,
        $isFounderOrHolderInDebt,
        $isFounderOrHolderInDebtDescription,
        array $threeYearsStatement,
        array $socialMedia,
        array $equityHolders,
        bool $isPublished = false)
    {

        $business = new Business();
        $business->entrepreneur_id = $entrepreneurId;
        $business->logo = $logo;
        $business->name = $name;
        $business->business_brief = $businessBrief;
        $business->industry = $industry;
        $business->year_founded = $yearFounded;
        $business->website = $website;
        $business->allow_reveal = $allowReveal;
        $business->existing_business = $existingBusiness;
        $business->has_commercial_license = $hasCommercialLicense;
        $business->jurisdiction_of_commercial_license = $jurisdictionOfCommercialLicense;
        $business->legal_structure = $legalStructure;
        $business->your_role_in_business = $yourRoleInBusiness;
        $business->reason_of_selling_equity = $reasonOfSellingEquity;
        $business->business_value = $businessValue;
        $business->equity_for_sale = $equityForSale;
        $business->asking_price = $askingPrice;
        $business->is_involved_in_any_proceedings = $isInvolvedInAnyProceedings;
        $business->is_involved_in_any_proceedings_description = $isInvolvedInAnyProceedingsDescription;
        $business->is_concern_with_business_employees = $isConcernWithBusinessEmployees;
        $business->is_concern_with_business_employees_description = $isConcernWithBusinessEmployeesDescription;
        $business->is_founder_or_holder_in_debt = $isFounderOrHolderInDebt;
        $business->is_founder_or_holder_in_debt_description = $isFounderOrHolderInDebtDescription;
        $business->three_years_statement = json_encode($threeYearsStatement);
        $business->social_media = json_encode($socialMedia);
        $business->equity_holders = json_encode($equityHolders);
        $business->is_published = $isPublished;

        $business->save();

        //  Create a verification model attached to business
        BusinessVerificationRepository::createBusinessVerification($business);

        new BusinessCreatedEvent($business->entrepreneur->user->id, $business->id);

        return $business;
    }

    public function payBusiness(int $businessId, float $price)
    {
        $business = $this->findById($businessId);
        $business->paid_at = Carbon::now();
        $business->save();

        $this->publishBusiness($businessId, $price);

        return $business;
    }

    /**
     * @param int $businessId
     *
     * @param bool $includeDeleted
     * @return Business
     */
    public function findById(int $businessId, bool $includeDeleted = false)
    {
        if ($includeDeleted)
            return Business::withTrashed()->find($businessId);
        else
            return Business::find($businessId);
    }

    public function publishBusiness(int $businessId, float $price)
    {
        $business = $this->findById($businessId);
        $business->is_published = true;
        $business->save();

        new BusinessPublishedEvent($business->entrepreneur->user->id, $business->id, $price);

        return $business;
    }

    public function unPublishBusiness(int $businessId)
    {
        $business = $this->findById($businessId);
        $business->is_published = false;
        $business->save();

        return $business;
    }

    public function paginate(
        int $page,
        $representativeId,
        $entrepreneurId,
        $industry,
        $revenueFrom,
        $revenueTo,
        $sponsor,
        $yearFounded,
        $legalStructure,
        $allowReveal,
        $existingBusiness,
        $perPage = 10
    )
    {
        $query = Business::query();

        if ($industry !== null) $query->whereIn('industry', json_decode($industry));

        if ($representativeId !== null) {
            $representative = (new RepresentativeRepository())->findById($representativeId);
            $query->where('entrepreneur_id', $representative->user->entrepreneur->id);
        }

        if ($entrepreneurId !== null)
            $query->where('entrepreneur_id', $entrepreneurId);

        if ($revenueFrom !== null)
            $query->where('three_years_statement->latest_operating_performance->revenue', '>=', (int)$revenueFrom);
        if ($revenueTo !== null)
            $query->where('three_years_statement->latest_operating_performance->revenue', '<=', (int)$revenueTo);

        //  TODO: implement sponsor filtering

        if ($yearFounded !== null)
            $query->where(DB::raw('YEAR(year_founded)'), '=', (int)$yearFounded);

        if ($legalStructure !== null) $query->whereIn('legal_structure', json_decode($legalStructure));

        if ($allowReveal !== null) $query->where('allow_reveal', $allowReveal);

        if ($existingBusiness !== null) $query->where('existing_business', $existingBusiness);

        return $query->paginate($perPage, $page);
    }

    /**
     * @param int $businessId
     * @param array $data
     *
     * @return Business
     */
    public function updateBusiness(int $businessId, array $data)
    {

        $business = $this->findById($businessId);

        if (array_has($data, 'logo'))
            $business->logo = $data['logo'];
        if (array_has($data, 'name'))
            $business->name = $data['name'];
        if (array_has($data, 'business_brief'))
            $business->business_brief = $data['business_brief'];
        if (array_has($data, 'industry'))
            $business->industry = $data['industry'];
        if (array_has($data, 'year_founded'))
            $business->year_founded = $data['year_founded'];
        if (array_has($data, 'website'))
            $business->website = $data['website'];
        if (array_has($data, 'allow_reveal'))
            $business->allow_reveal = $data['allow_reveal'];
        if (array_has($data, 'existing_business'))
            $business->existing_business = $data['existing_business'];
        if (array_has($data, 'has_commercial_license'))
            $business->has_commercial_license = $data['has_commercial_license'];
        if (array_has($data, 'jurisdiction_of_commercial_license'))
            $business->jurisdiction_of_commercial_license = $data['jurisdiction_of_commercial_license'];
        if (array_has($data, 'legal_structure'))
            $business->legal_structure = $data['legal_structure'];
        if (array_has($data, 'your_role_in_business'))
            $business->your_role_in_business = $data['your_role_in_business'];
        if (array_has($data, 'reason_of_selling_equity'))
            $business->reason_of_selling_equity = $data['reason_of_selling_equity'];
        if (array_has($data, 'business_value'))
            $business->business_value = $data['business_value'];
        if (array_has($data, 'equity_for_sale'))
            $business->equity_for_sale = $data['equity_for_sale'];
        if (array_has($data, 'asking_price'))
            $business->equity_for_sale = $data['asking_price'];
        if (array_has($data, 'is_involved_in_any_proceedings'))
            $business->is_involved_in_any_proceedings = $data['is_involved_in_any_proceedings'];
        if (array_has($data, 'is_involved_in_any_proceedings_description'))
            $business->is_involved_in_any_proceedings_description = $data['is_involved_in_any_proceedings_description'];
        if (array_has($data, 'is_concern_with_business_employees'))
            $business->is_concern_with_business_employees = $data['is_concern_with_business_employees'];
        if (array_has($data, 'is_concern_with_business_employees_description'))
            $business->is_concern_with_business_employees_description = $data['is_concern_with_business_employees_description'];
        if (array_has($data, 'is_founder_or_holder_in_debt'))
            $business->is_founder_or_holder_in_debt = $data['is_founder_or_holder_in_debt'];
        if (array_has($data, 'is_founder_or_holder_in_debt_description'))
            $business->is_founder_or_holder_in_debt_description = $data['is_founder_or_holder_in_debt_description'];
        if (array_has($data, 'three_years_statement'))
            $business->three_years_statement = json_encode($data['three_years_statement']);
        if (array_has($data, 'social_media'))
            $business->social_media = json_encode($data['social_media']);
        if (array_has($data, 'equity_holders'))
            $business->equity_holders = json_encode($data['equity_holders']);

        $business->save();

        return $business;
    }

    /**
     * @param int $businessId
     *
     * @throws \Exception
     */
    public function deleteBusiness(int $businessId)
    {
        $business = $this->findById($businessId);
        $business->delete();
    }

    public function paginateViewedBusinesses($investor, $page)
    {
        self::removeExpiredViewed();
        return $investor->viewed_businesses()
            ->whereNull('denora_duebus_investor_view.deleted_at')
            ->paginate(10, $page);
    }

    public function viewBusiness($investor, int $businessId, float $price)
    {
        $investorView = new InvestorView();
        $investorView->investor_id = $investor->id;
        $investorView->business_id = $businessId;
        $investorView->save();

        new BusinessViewedEvent($investor->user->id, $businessId, $price);
    }

    public function revealBusiness($investor, int $businessId, float $price)
    {
        $investor->revealed_businesses()->syncWithoutDetaching($businessId);

        new BusinessRevealedEvent($investor->user->id, $businessId, $price);
    }

    /**
     * @param $industry
     * @param null $isPublished
     * @return int
     */
    public function countAll($industry = null, $isPublished = null): int
    {
        $query = Business::query();
        if ($industry != null)
            $query->where('industry', $industry);
        if ($isPublished != null)
            $query->where('is_published', $isPublished);
        return $query->count();
    }

}
