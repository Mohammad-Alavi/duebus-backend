<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Entrepreneur;
use Denora\Duebusverification\Classes\Repositories\EntrepreneurVerificationRepository;

class EntrepreneurRepository {

    /**
     * @param int $entrepreneurId
     *
     * @return Entrepreneur
     */
    public function findById(int $entrepreneurId) {
        return Entrepreneur::find($entrepreneurId);
    }

    public function getOwnedBusinessIds(int $entrepreneurId){
        $entrepreneur = $this->findById($entrepreneurId);
        return $entrepreneur->businesses->pluck('id')->toArray();
    }

    /**
     * @param int    $userId
     *
     * @param string $educations
     * @param string $experiences
     *
     * @return Entrepreneur
     */
    public function createEntrepreneur(int $userId, string $educations = null, string $experiences = null) {

        $entrepreneur = new Entrepreneur();
        $entrepreneur->user_id = $userId;
        $entrepreneur->educations = $educations;
        $entrepreneur->experiences = $experiences;

        $entrepreneur->save();

        //  Create a verification model attached to entrepreneur
        EntrepreneurVerificationRepository::createEntrepreneurVerification($entrepreneur);


        return $entrepreneur;
    }

    /**
     * @param int   $entrepreneurId
     * @param array $data
     *
     * @return Entrepreneur
     */
    public function updateEntrepreneur(int $entrepreneurId, array $data) {

        $entrepreneur = $this->findById($entrepreneurId);

        if (array_has($data, 'educations'))
            $entrepreneur->educations = $data['educations'];
        if (array_has($data, 'experiences'))
            $entrepreneur->experiences = $data['experiences'];

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
