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
     * @param $entrepreneurVerification
     *
     * @return mixed
     */
    static public function createAssociationArticle(
        $entrepreneurVerification
    )
    {
        $associationArticle = new AssociationArticle();
        $entrepreneurVerification->article_of_association()->save($associationArticle);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($associationArticle);

        return $entrepreneurVerification;
    }

    /**
     * @param $entrepreneurVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateAssociationArticle($entrepreneurVerification, $image, $description)
    {
        $associationArticle = $entrepreneurVerification->article_of_association;
        $associationArticle->image = $image;
        $entrepreneurVerification->article_of_association()->save($associationArticle);
        ItemRepository::updateItem($associationArticle, $description);

        return $entrepreneurVerification;
    }
}
