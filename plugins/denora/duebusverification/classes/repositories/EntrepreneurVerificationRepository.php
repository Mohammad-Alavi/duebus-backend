<?php namespace Denora\Duebusverification\Classes\Repositories;


use Denora\Duebusprofile\Models\Entrepreneur;
use Denora\Duebusverification\Models\EntrepreneurVerification;

class EntrepreneurVerificationRepository
{

    /**
     * @param int $id
     *
     * @return mixed
     */
    static public function findById(int $id)
    {
        return EntrepreneurVerification::find($id);
    }

    /**
     * @param $entrepreneur
     *
     * @return Entrepreneur
     */
    static public function createEntrepreneurVerification(
        $entrepreneur
    )
    {
        $entrepreneurVerification = new EntrepreneurVerification();
        $entrepreneur->verification()->save($entrepreneurVerification);

        //  Create a id_of_managing_partner model attached to entrepreneurVerification
        ManagingPartnerIDRepository::createManagingPartnerID($entrepreneurVerification);

        //  Create a article_of_association model attached to entrepreneurVerification
        AssociationArticleRepository::createAssociationArticle($entrepreneurVerification);

        //  Create a commercial_license model attached to entrepreneurVerification
        CommercialLicenseRepository::createCommercialLicense($entrepreneurVerification);

        //  Create a chamber_of_commerce model attached to entrepreneurVerification
        ChamberOfCommerceRepository::createChamberOfCommerce($entrepreneurVerification);

        return $entrepreneur;
    }
}
