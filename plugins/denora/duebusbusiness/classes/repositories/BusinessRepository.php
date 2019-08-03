<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Denora\Duebusbusiness\Models\Business;

class BusinessRepository {

    /**
     * @param int $businessId
     *
     * @return Business
     */
    public function findById(int $businessId) {
        return Business::find($businessId);
    }

    /**
     * @param int         $userId
     * @param string      $name
     *
     * @param string      $industry
     * @param int         $yearFounded
     * @param string|null $website
     * @param bool        $allowReveal
     * @param bool        $existingBusiness
     * @param string      $yourRoleInBusiness
     * @param string      $reasonOfSellingEquity
     * @param int         $businessValue
     * @param float       $equityForSale
     * @param bool        $isInvolvedInAnyProceedings
     * @param bool        $isConcernWithBusinessEmployees
     * @param bool        $isFounderOrHolderInDebt
     *
     * @return Business
     */
    public function createBusiness(
        int $userId,
        string $name,
        string $industry,
        int $yearFounded,
        $website,
        bool $allowReveal,
        bool $existingBusiness,
        string $yourRoleInBusiness,
        string $reasonOfSellingEquity,
        int $businessValue,
        float $equityForSale,
        bool $isInvolvedInAnyProceedings,
        bool $isConcernWithBusinessEmployees,
        bool $isFounderOrHolderInDebt) {

        $business = new Business();
        $business->user_id = $userId;
        $business->name = $name;
        $business->industry = $industry;
        $business->year_founded = $yearFounded;
        $business->website = $website;
        $business->allow_reveal = $allowReveal;
        $business->existing_business = $existingBusiness;
        $business->your_role_in_business = $yourRoleInBusiness;
        $business->reason_of_selling_equity = $reasonOfSellingEquity;
        $business->business_value = $businessValue;
        $business->equity_for_sale = $equityForSale;
        $business->is_involved_in_any_proceedings = $isInvolvedInAnyProceedings;
        $business->is_concern_with_business_employees = $isConcernWithBusinessEmployees;
        $business->is_founder_or_holder_in_debt = $isFounderOrHolderInDebt;

        $business->save();

        return $business;
    }

    /**
     * @param int   $businessId
     * @param array $data
     *
     * @return Business
     */
    public function updateBusiness(int $businessId, array $data) {

        $business = $this->findById($businessId);

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
    public function deleteBusiness(int $businessId) {
        $business = $this->findById($businessId);
        $business->delete();
    }

}
