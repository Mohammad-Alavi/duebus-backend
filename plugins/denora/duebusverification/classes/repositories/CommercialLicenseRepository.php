<?php namespace Denora\Duebusverification\Classes\Repositories;

use Denora\Duebusverification\Models\CommercialLicense;

class CommercialLicenseRepository
{

    /**
     * @param int $id
     *
     * @return mixed
     */
    static public function findById(int $id)
    {
        return CommercialLicense::find($id);
    }

    /**
     * @param $entrepreneurVerification
     *
     * @return mixed
     */
    static public function createCommercialLicense(
        $entrepreneurVerification
    )
    {
        $commercialLicense = new CommercialLicense();
        $entrepreneurVerification->commercial_license()->save($commercialLicense);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($commercialLicense);

        return $entrepreneurVerification;
    }

    /**
     * @param $entrepreneurVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateCommercialLicense($entrepreneurVerification, $image, $description)
    {
        $commercialLicense = $entrepreneurVerification->commercial_license;
        $commercialLicense->image = $image;
        $entrepreneurVerification->commercial_license()->save($commercialLicense);
        ItemRepository::updateItem($commercialLicense, $description);

        return $entrepreneurVerification;
    }
}
