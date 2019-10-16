<?php namespace Denora\Duebusverification\Classes\Repositories;


use Denora\Duebusverification\Models\Passport;

class PassportRepository {

    /**
     * @param int $passportId
     *
     * @return mixed
     */
    static public function findById(int $passportId) {
        return Passport::find($passportId);
    }

    /**
     * @param $investorVerification
     *
     * @return mixed
     */
    static public function createPassport(
        $investorVerification
    ) {
        $passport = new Passport();
        $investorVerification->passport()->save($passport);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($passport);

        return $investorVerification;
    }

    /**
     * @param $investorVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updatePassport($investorVerification, $image, $description) {
        $passport = $investorVerification->passport;
        $passport->image = $image;
        $investorVerification->passport()->save($passport);
        ItemRepository::updateItem($passport, $description);

        return $investorVerification;
    }
}