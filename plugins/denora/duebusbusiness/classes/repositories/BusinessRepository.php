<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Denora\Duebusbusiness\Models\Business;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;

class BusinessRepository {

    /**
     * @param int $businessId
     *
     * @return Business
     */
    public function findById(int $businessId) {
        return Business::find($businessId);
    }

    /**
     * @param int    $userId
     * @param string $name
     *
     * @return Business
     */
    public function createBusiness(int $userId, string $name) {
        $userRepository = new UserRepository();

        $business = new Business();
        $business->name = $name;

        $business->save();

        return $business;
    }

    /**
     * @param int   $businessId
     * @param array $data
     *
     * @return Business
     */
    public function updateBusiness(int $businessId, array $data) {

        $business = $this->findById($businessId);

        if (array_has($data, 'name'))
            $business->name = $data['name'];

        $business->save();

        return $business;
    }

    /**
     * @param int $businessId
     *
     * @throws \Exception
     */
    public function deleteBusiness(int $businessId) {
        $business = $this->findById($businessId);
        $business->delete();
    }

}
