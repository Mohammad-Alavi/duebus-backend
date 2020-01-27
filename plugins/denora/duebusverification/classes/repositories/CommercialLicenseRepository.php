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
     * @param $businessVerification
     *
     * @return mixed
     */
    static public function createCommercialLicense(
        $businessVerification
    )
    {
        $commercialLicense = new CommercialLicense();
        $businessVerification->commercial_license()->save($commercialLicense);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($commercialLicense);

        return $businessVerification;
    }

    /**
     * @param $businessVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateCommercialLicense($businessVerification, $image, $description)
    {
        $commercialLicense = $businessVerification->commercial_license;
        $commercialLicense->image = $image;
        $businessVerification->commercial_license()->save($commercialLicense);
        ItemRepository::updateItem($commercialLicense, $description);

        return $businessVerification;
    }
}
