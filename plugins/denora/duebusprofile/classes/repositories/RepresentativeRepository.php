<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusbusiness\Http\BusinessController;
use Denora\Duebusprofile\Models\Representative;

class RepresentativeRepository
{

    /**
     * @param int $representativeId
     *
     * @return Representative
     */
    public function findById(int $representativeId)
    {
        return Representative::find($representativeId);
    }

    /**
     * @param int $userId
     *
     * @param string $numberOfClients
     * @param string $interestedIn
     *
     * @param string|null $range_of_investment
     * @param string $sectors
     * @param string|null $businessName
     * @param string|null $yearFounded
     * @param string|null $website
     *
     * @param array $socialMedia
     *
     * @return Representative
     */
    public function createRepresentative(
        int $userId,
        string $numberOfClients = null,
        $interestedIn = '[]',
        string $range_of_investment = null,
        $sectors = '[]',
        string $businessName = null,
        $yearFounded = null,
        string $website = null,
        array $socialMedia = []
    )
    {

        $representative = new Representative();
        $representative->user_id = $userId;
        $representative->number_of_clients = $numberOfClients;
        $representative->interested_in = $interestedIn;
        $representative->range_of_investment = $range_of_investment;
        $representative->business_name = $businessName;
        $representative->year_founded = $yearFounded;
        $representative->website = $website;
        $representative->social_media = json_encode($socialMedia);

        $representative->save();
        $representative->sectors()->sync(json_decode($sectors));

        // Create investor profile if not existing
        if (!$representative->user->investor)
            (new InvestorRepository())->createInvestor(
                $userId,
                $range_of_investment,
                null,
                $sectors
            );

        // Create entrepreneur profile if not existing
        if (!$representative->user->entrepreneur)
            (new EntrepreneurRepository())->createEntrepreneur($userId);


        return $representative;
    }

    /**
     * @param int $representativeId
     * @param array $data
     *
     * @param bool $updateInvestorToo
     * @return Representative
     */
    public function updateRepresentative(int $representativeId, array $data, bool $updateInvestorToo = true)
    {

        $representative = $this->findById($representativeId);

        if (array_has($data, 'business_name'))
            $representative->business_name = $data['business_name'];
        if (array_has($data, 'year_founded'))
            $representative->year_founded = $data['year_founded'];
        if (array_has($data, 'website'))
            $representative->website = $data['website'];
        if (array_has($data, 'number_of_clients'))
            $representative->number_of_clients = $data['number_of_clients'];
        if (array_has($data, 'interested_in'))
            $representative->interested_in = $data['interested_in'];
        if (array_has($data, 'range_of_investment'))
            $representative->range_of_investment = $data['range_of_investment'];
        if (array_has($data, 'sectors'))
            $representative->sectors()->sync(json_decode($data['sectors']));
        if (array_has($data, 'social_media'))
            $representative->social_media = json_encode(BusinessController::generateSocialMedia($data));

        $representative->save();

        //  Update investor if exists
        $investor = $representative->user->investor;
        if ($investor && $updateInvestorToo)
            (new InvestorRepository())->updateInvestor($investor->id, $data, false);

        return $representative;
    }

    static public function paginate(int $page)
    {
        $query = Representative::query();

        return $query->paginate(1000, $page);
    }


}
