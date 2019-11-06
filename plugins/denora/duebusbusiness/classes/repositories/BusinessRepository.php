<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Denora\Duebusbusiness\Models\Business;
use Denora\Notification\Classes\Events\BusinessCreatedEvent;
use Denora\Notification\Classes\Events\BusinessPublishedEvent;
use Illuminate\Support\Facades\DB;

class BusinessRepository
{

    /**
     * @param int $entrepreneurId
     * @param             $logo
     * @param string $name
     *
     * @param string $industry
     * @param int $yearFounded
     * @param string|null $website
     * @param bool $allowReveal
     * @param bool $existingBusiness
     * @param string $legalStructure
     * @param string $yourRoleInBusiness
     * @param string $reasonOfSellingEquity
     * @param float $businessValue
     * @param float $equityForSale
     * @param float $askingPrice
     * @param bool $isInvolvedInAnyProceedings
     * @param bool $isConcernWithBusinessEmployees
     * @param bool $isFounderOrHolderInDebt
     *
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
        string $industry,
        $yearFounded,
        $website,
        bool $allowReveal,
        bool $existingBusiness,
        $legalStructure,
        string $yourRoleInBusiness,
        string $reasonOfSellingEquity,
        float $businessValue,
        float $equityForSale,
        float $askingPrice,
        $isInvolvedInAnyProceedings,
        $isConcernWithBusinessEmployees,
        $isFounderOrHolderInDebt,
        array $threeYearsStatement,
        array $socialMedia,
        array $equityHolders,
        bool $isPublished = false)
    {

        $business = new Business();
        $business->entrepreneur_id = $entrepreneurId;
        $business->logo = $logo;
        $business->name = $name;
        $business->industry = $industry;
        $business->year_founded = $yearFounded;
        $business->website = $website;
        $business->allow_reveal = $allowReveal;
        $business->existing_business = $existingBusiness;
        $business->legal_structure = $legalStructure;
        $business->your_role_in_business = $yourRoleInBusiness;
        $business->reason_of_selling_equity = $reasonOfSellingEquity;
        $business->business_value = $businessValue;
        $business->equity_for_sale = $equityForSale;
        $business->asking_price = $askingPrice;
        $business->is_involved_in_any_proceedings = $isInvolvedInAnyProceedings;
        $business->is_concern_with_business_employees = $isConcernWithBusinessEmployees;
        $business->is_founder_or_holder_in_debt = $isFounderOrHolderInDebt;
        $business->three_years_statement = json_encode($threeYearsStatement);
        $business->social_media = json_encode($socialMedia);
        $business->equity_holders = json_encode($equityHolders);
        $business->is_published = $isPublished;

        $business->save();

        new BusinessCreatedEvent($business->entrepreneur->user->id, $business->id);

        return $business;
    }

    public function publishBusiness(int $businessId)
    {
        $business = $this->findById($businessId);
        $business->is_published = true;
        $business->save();

        new BusinessPublishedEvent($business->entrepreneur->user->id, $business->id);

        return $business;
    }

    public function unPublishBusiness(int $businessId)
    {
        $business = $this->findById($businessId);
        $business->is_published = false;
        $business->save();

        return $business;
    }
    /**
     * @param int $businessId
     *
     * @return Business
     */
    public function findById(int $businessId)
    {
        return Business::find($businessId);
    }

    public function paginate(
        int $page,
        $entrepreneurId,
        $industry,
        $revenueFrom,
        $revenueTo,
        $sponsor,
        $yearFounded,
        $legalStructure,
        $allowReveal,
        $existingBusiness
    ){
        $query = Business::query();

        if ($industry !== null) $query->whereIn('industry', json_decode($industry));

        if ($entrepreneurId !== null)
            $query->where('entrepreneur_id', $entrepreneurId);

        if ($revenueFrom !== null)
            $query->where('three_years_statement->latest_operating_performance->revenue', '>=', (int)$revenueFrom);
        if ($revenueTo !== null)
            $query->where('three_years_statement->latest_operating_performance->revenue', '<=', (int)$revenueTo);

        //  TODO: implement sponsor filtering

        if ($yearFounded !== null)
            $query->where( DB::raw('YEAR(year_founded)'), '=', (int)$yearFounded );

        if ($legalStructure !== null) $query->whereIn('legal_structure', json_decode($legalStructure));

        if ($allowReveal !== null) $query->where('allow_reveal', $allowReveal);

        if ($existingBusiness !== null) $query->where('existing_business', $existingBusiness);

        return $query->paginate(10, $page);
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
        if (array_has($data, 'is_involved_in_any_proceedings'))
            $business->is_involved_in_any_proceedings = $data['is_involved_in_any_proceedings'];
        if (array_has($data, 'is_concern_with_business_employees'))
            $business->is_concern_with_business_employees = $data['is_concern_with_business_employees'];
        if (array_has($data, 'is_founder_or_holder_in_debt'))
            $business->is_founder_or_holder_in_debt = $data['is_founder_or_holder_in_debt'];

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

    public function viewBusiness($investor, int $businessId){
        $investor->viewed_businesses()->syncWithoutDetaching($businessId);
    }

    public function revealBusiness($investor, int $businessId){
        $investor->revealed_businesses()->syncWithoutDetaching($businessId);
    }

    /**
     * @return int
     */
    public function countAll(): int
    {
        return Business::all()->count();
    }

}
