<?php namespace Denora\Duebusverification\Classes\Repositories;

use Denora\Duebusverification\Models\ManagingPartnerID;

class ManagingPartnerIDRepository
{

    /**
     * @param int $id
     *
     * @return mixed
     */
    static public function findById(int $id)
    {
        return ManagingPartnerID::find($id);
    }

    /**
     * @param $businessVerification
     *
     * @return mixed
     */
    static public function createManagingPartnerID(
        $businessVerification
    )
    {
        $managingPartnerID = new ManagingPartnerID();
        $businessVerification->id_of_managing_partner()->save($managingPartnerID);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($managingPartnerID);

        return $businessVerification;
    }

    /**
     * @param $businessVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateManagingPartnerID($businessVerification, $image, $description)
    {
        $managingPartnerID = $businessVerification->id_of_managing_partner;
        $managingPartnerID->image = $image;
        $businessVerification->id_of_managing_partner()->save($managingPartnerID);
        ItemRepository::updateItem($managingPartnerID, $description);

        return $businessVerification;
    }
}
