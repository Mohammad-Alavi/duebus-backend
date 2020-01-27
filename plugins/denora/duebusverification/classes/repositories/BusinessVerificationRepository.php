<?php namespace Denora\Duebusverification\Classes\Repositories;

use Denora\Duebusbusiness\Models\Business;
use Denora\Duebusverification\Models\BusinessVerification;

class BusinessVerificationRepository
{

    /**
     * @param int $id
     *
     * @return mixed
     */
    static public function findById(int $id)
    {
        return BusinessVerification::find($id);
    }

    /**
     * @param $business
     *
     * @return Business
     */
    static public function createBusinessVerification(
        $business
    )
    {
        $businessVerification = new BusinessVerification();
        $business->verification()->save($businessVerification);

        //  Create a id_of_managing_partner model attached to businessVerification
        ManagingPartnerIDRepository::createManagingPartnerID($businessVerification);

        //  Create a article_of_association model attached to businessVerification
        AssociationArticleRepository::createAssociationArticle($businessVerification);

        //  Create a commercial_license model attached to businessVerification
        CommercialLicenseRepository::createCommercialLicense($businessVerification);

        //  Create a chamber_of_commerce model attached to businessVerification
        ChamberOfCommerceRepository::createChamberOfCommerce($businessVerification);

        return $business;
    }
}
