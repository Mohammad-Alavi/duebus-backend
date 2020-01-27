<?php namespace Denora\Duebusverification\Classes\Repositories;

use Denora\Duebusverification\Models\AssociationArticle;

class AssociationArticleRepository
{

    /**
     * @param int $id
     *
     * @return mixed
     */
    static public function findById(int $id)
    {
        return AssociationArticle::find($id);
    }

    /**
     * @param $businessVerification
     *
     * @return mixed
     */
    static public function createAssociationArticle(
        $businessVerification
    )
    {
        $associationArticle = new AssociationArticle();
        $businessVerification->article_of_association()->save($associationArticle);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($associationArticle);

        return $businessVerification;
    }

    /**
     * @param $businessVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateAssociationArticle($businessVerification, $image, $description)
    {
        $associationArticle = $businessVerification->article_of_association;
        $associationArticle->image = $image;
        $businessVerification->article_of_association()->save($associationArticle);
        ItemRepository::updateItem($associationArticle, $description);

        return $businessVerification;
    }
}
