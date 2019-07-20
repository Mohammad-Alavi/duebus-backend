<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Investor;

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
     * @param int $userId
     *
     * @param int $investmentsFrom
     * @param int $investmentsTo
     * @param int $businessesInvestedIn
     *
     * @return Investor
     */
    public function createInvestor(int $userId, int $investmentsFrom, int $investmentsTo, int $businessesInvestedIn) {

        $investor = new Investor();
        $investor->investments_from = $investmentsFrom;
        $investor->investments_to = $investmentsTo;
        $investor->businesses_invested_in = $businessesInvestedIn;
        $investor->user_id = $userId;

        $investor->save();

        return $investor;
    }

    /**
     * @param int   $investorId
     * @param array $data
     *
     * @return Investor
     */
    public function updateInvestor(int $investorId, array $data) {

        $investor = $this->findById($investorId);

        if (array_has($data, 'investments_from'))
            $investor->investments_from = $data['investments_from'];
        if (array_has($data, 'investments_to'))
            $investor->investments_to = $data['investments_to'];
        if (array_has($data, 'businesses_invested_in'))
            $investor->businesses_invested_in = $data['businesses_invested_in'];

        $investor->save();

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
