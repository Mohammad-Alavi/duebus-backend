<?php namespace Denora\Duebus\Classes\Repositories;

use Denora\Duebus\Models\Representative;

class RepresentativeRepository {

    /**
     * @param int $representativeId
     *
     * @return Representative
     */
    public function findById(int $representativeId) {
        return Representative::find($representativeId);
    }

    /**
     * @param int $userId
     *
     * @return Representative
     */
    public function createRepresentative(int $userId) {

        $representative = new Representative();
        $representative->user_id = $userId;

        $representative->save();

        return $representative;
    }

    /**
     * @param int $representativeId
     *
     * @throws \Exception
     */
    public function deleteRepresentative(int $representativeId) {
        $representative = $this->findById($representativeId);
        $representative->delete();
    }

}
