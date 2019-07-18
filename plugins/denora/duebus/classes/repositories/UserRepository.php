<?php


use RainLab\User\Models\User;

class UserRepository {

    /**
     * @param int $userId
     *
     * @return User
     */
    public function findById(int $userId){
        return User::find($userId);
    }

    /**
     * @param string $userEmail
     *
     * @return User
     */
    public function findByEmail(string $userEmail){
        return User::findByEmail($userEmail);
    }

    /**
     * @param int   $userId
     * @param array $values
     */
    public function updateUser(int $userId, array $values){

    }

}