<?php namespace Denora\Duebusverification\Classes\Repositories;


use Denora\Duebusprofile\Models\Investor;
use Denora\Duebusverification\Models\InvestorVerification;

class InvestorVerificationRepository {

    /**
     * @param int $investorVerificationId
     *
     * @return mixed
     */
    static public function findById(int $investorVerificationId) {
        return InvestorVerification::find($investorVerificationId);
    }

    /**
     * @param $investor
     *
     * @return Investor
     */
    static public function createInvestorVerification(
        $investor
    ) {
        $investorVerification = new InvestorVerification();
        $investor->verification()->save($investorVerification);

        //  Create a passport model attached to investorVerification
        PassportRepository::createPassport($investorVerification);

        //  Create a identification model attached to investorVerification
        IdentificationRepository::createIdentification($investorVerification);

        return $investor;
    }
}