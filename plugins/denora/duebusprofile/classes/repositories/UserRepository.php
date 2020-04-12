<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Carbon\Carbon;
use Denora\Duebus\Classes\Repositories\PackageRepository;
use Denora\Notification\Classes\Events\WalletChargedEvent;
use RainLab\User\Models\User;

class UserRepository
{

    /**
     * @param string $userEmail
     *
     * @return User
     */
    public function findByEmail(string $userEmail)
    {
        return User::findByEmail($userEmail);
    }

    /**
     * @param int $userId
     * @param array $data
     *
     * @return User
     */
    public function updateUser(int $userId, array $data)
    {

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
     *
     * @return User
     */
    public function findById(int $userId)
    {
        return User::find($userId);
    }

    /**
     * @param int $userId
     * @param $packageId
     */
    public function chargeWallet(int $userId, $packageId)
    {
        $package = (new PackageRepository())->findById($packageId);
        $user = $this->findById($userId);
        $user->point = $user->points + $package->points;
        $user->point_expires_at = Carbon::now()->addMonths($package->validity_months)->addDays($package->validity_days);
        $user->save();
        new WalletChargedEvent($userId, $package);
    }

    public function countAllUsers()
    {
        return User::query()->count();
    }

    public function countAllInvestors(bool $countAlsoRepresentatives = false)
    {
        $query = User::query()->has('investor');
        if (!$countAlsoRepresentatives)
            $query->doesntHave('representative');
        return $query->count();
    }

    public function countAllEntrepreneurs(bool $countAlsoRepresentatives = false)
    {
        $query = User::query()->has('entrepreneur');
        if (!$countAlsoRepresentatives)
            $query->doesntHave('representative');
        return $query->count();
    }

    public function countAllRepresentatives()
    {
        $query = User::query()->has('representative');
        return $query->count();
    }

}
