<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Entrepreneur;

class EntrepreneurRepository {

    /**
     * @param int $entrepreneurId
     *
     * @return Entrepreneur
     */
    public function findById(int $entrepreneurId) {
        return Entrepreneur::find($entrepreneurId);
    }

    /**
     * @param int $userId
     *
     * @return Entrepreneur
     */
    public function createEntrepreneur(int $userId) {

        $entrepreneur = new Entrepreneur();
        $entrepreneur->user_id = $userId;

        $entrepreneur->save();

        return $entrepreneur;
    }

    /**
     * @param int $entrepreneurId
     *
     * @throws \Exception
     */
    public function deleteEntrepreneur(int $entrepreneurId) {
        $entrepreneur = $this->findById($entrepreneurId);
        $entrepreneur->delete();
    }

}
