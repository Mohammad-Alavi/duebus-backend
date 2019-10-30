<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Investor;
use Denora\Duebusverification\Classes\Repositories\InvestorVerificationRepository;
use Denora\Duebusverification\Models\InvestorVerification;

class InvestorRepository {

    /**
     * @param int $investorId
     *
     * @return Investor
     */
    public function findById(int $investorId) {
        return Investor::find($investorId);
    }

    /**
     * @param int    $userId
     *
     * @param string $range_of_investment
     * @param string $range_of_businesses_invested_in
     *
     * @param string $sectors
     *
     * @return Investor
     */
    public function createInvestor(
        int $userId,
        string $range_of_investment = null,
        string $range_of_businesses_invested_in = null,
        $sectors = '[]'
    ) {

        $investor = new Investor();
        $investor->range_of_investment = $range_of_investment;
        $investor->range_of_businesses_invested_in = $range_of_businesses_invested_in;
        $investor->user_id = $userId;

        $investor->save();
        $investor->sectors()->sync(json_decode($sectors));

        //  Create a verification model attached to investor
        InvestorVerificationRepository::createInvestorVerification($investor);

        return $investor;
    }

    /**
     * @param int $investorId
     * @param array $data
     *
     * @param bool $updateRepresentativeToo
     * @return Investor
     */
    public function updateInvestor(int $investorId, array $data, bool $updateRepresentativeToo = true) {

        $investor = $this->findById($investorId);

        if (array_has($data, 'range_of_investment'))
            $investor->range_of_investment = $data['range_of_investment'];
        if (array_has($data, 'range_of_businesses_invested_in'))
            $investor->range_of_businesses_invested_in = $data['range_of_businesses_invested_in'];
        if (array_has($data, 'sectors'))
            $investor->sectors()->sync(json_decode($data['sectors']));

        $investor->save();

        //  Update representative if exists
        $representative = $investor->user->representative;
        if ($representative && $updateRepresentativeToo)
            (new RepresentativeRepository())->updateRepresentative($representative->id, $data, false);

        return $investor;
    }

    /**
     * @param int $investorId
     *
     * @throws \Exception
     */
    public function deleteInvestor(int $investorId) {
        $investor = $this->findById($investorId);
        $investor->delete();
    }

}
