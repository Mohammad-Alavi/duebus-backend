<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Carbon\Carbon;
use Denora\Notification\Classes\Events\WalletChargedEvent;
use RainLab\User\Models\User;

class UserRepository {

    /**
     * @param int $userId
     *
     * @return User
     */
    public function findById(int $userId) {
        return User::find($userId);
    }

    /**
     * @param string $userEmail
     *
     * @return User
     */
    public function findByEmail(string $userEmail) {
        return User::findByEmail($userEmail);
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return User
     */
    public function updateUser(int $userId, array $data) {

        $user = $this->findById($userId);

        if (array_has($data, 'name')) $user->name = $data['name'];
        if (array_has($data, 'surname')) $user->surname = $data['surname'];
        if (array_has($data, 'avatar')) $user->avatar = $data['avatar'];
        if (array_has($data, 'current_password')) {
            //  Update password
            $user->password = $data['new_password'];
            $user->password_confirmation = $data['password_confirmation'];
        }

        $user->save();

        return $user;
    }

    /**
     * @param int $userId
     * @param int $points
     */
    public function chargeWallet(int $userId, int $points) {
        $user = $this->findById($userId);
        $user->point = $user->points + $points;
        $user->point_expires_at = Carbon::now()->addMonths(12);
        $user->save();
        new WalletChargedEvent($userId, $points);
    }

}
