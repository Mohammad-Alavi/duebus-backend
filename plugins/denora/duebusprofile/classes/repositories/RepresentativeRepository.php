<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Representative;

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
     * @param int    $userId
     *
     * @param string $numberOfClients
     * @param string $interestedIn
     *
     * @return Representative
     */
    public function createRepresentative(int $userId, string $numberOfClients = null, string $interestedIn = null) {

        $representative = new Representative();
        $representative->user_id = $userId;
        $representative->number_of_clients = $numberOfClients;
        $representative->interested_in = $interestedIn;

        $representative->save();

        return $representative;
    }

    /**
     * @param int   $representativeId
     * @param array $data
     *
     * @return Representative
     */
    public function updateRepresentative(int $representativeId, array $data) {

        $representative = $this->findById($representativeId);

        if (array_has($data, 'number_of_clients'))
            $representative->number_of_clients = $data['number_of_clients'];
        if (array_has($data, 'interested_in'))
            $representative->interested_in = $data['interested_in'];

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
