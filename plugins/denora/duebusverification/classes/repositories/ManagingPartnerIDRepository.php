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
     * @param $entrepreneurVerification
     *
     * @return mixed
     */
    static public function createManagingPartnerID(
        $entrepreneurVerification
    )
    {
        $managingPartnerID = new ManagingPartnerID();
        $entrepreneurVerification->id_of_managing_partner()->save($managingPartnerID);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($managingPartnerID);

        return $entrepreneurVerification;
    }

    /**
     * @param $entrepreneurVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateManagingPartnerID($entrepreneurVerification, $image, $description)
    {
        $managingPartnerID = $entrepreneurVerification->id_of_managing_partner;
        $managingPartnerID->image = $image;
        $entrepreneurVerification->id_of_managing_partner()->save($managingPartnerID);
        ItemRepository::updateItem($managingPartnerID, $description);

        return $entrepreneurVerification;
    }
}
