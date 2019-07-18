<?php namespace Denora\Duebus\Classes\Transformers;

use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

class ProfileTransformer {

    /**
     * @return array
     */
    static function transform() {
        /** @var User $user */
        $user = Auth::user();

        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'surname'      => $user->surname,
            'username'     => $user->username,
            'email'        => $user->email,
            'is_activated' => $user->is_activated,
            'point'        => $user->point,
            'is_superuser' => $user->is_superuser,

            'investor_profile'       => $user->investor ? InvestorTransformer::transform($user->investor) : null,
            'entrepreneur_profile'   => $user->entrepreneur ? EntrepreneurTransformer::transform($user->entrepreneur) : null,
            'representative_profile' => $user->representative ? RepresentativeTransformer::transform($user->representative) : null,

            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

}