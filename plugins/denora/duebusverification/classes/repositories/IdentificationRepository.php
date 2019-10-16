<?php namespace Denora\Duebusverification\Classes\Repositories;


use Denora\Duebusverification\Models\Identification;

class IdentificationRepository {

    /**
     * @param int $IdentificationId
     *
     * @return mixed
     */
    static public function findById(int $IdentificationId) {
        return Identification::find($IdentificationId);
    }

    /**
     * @param $investorVerification
     *
     * @return mixed
     */
    static public function createIdentification(
        $investorVerification
    ) {
        $identification = new Identification();
        $investorVerification->identification()->save($identification);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($identification);

        return $investorVerification;
    }

    /**
     * @param $investorVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateIdentification($investorVerification, $image, $description) {
        $identification = $investorVerification->identification;
        $identification->image = $image;
        $investorVerification->identification()->save($identification);
        ItemRepository::updateItem($identification, $description);

        return $investorVerification;
    }


}