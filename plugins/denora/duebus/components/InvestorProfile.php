<?php namespace Denora\Duebus\Components;

use Cms\Classes\ComponentBase;
use Denora\Duebus\Models\Entrepreneur;
use RainLab\User\Models\User;
use UserRepository;

class InvestorProfile extends ComponentBase {

    /**
     * @var int
     */
    public $userId;

    /**
     * @var Entrepreneur
     */
    public $investor;

    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails() {
        return [
            'name'        => 'Investor Profile',
            'description' => 'A section where investor fields of a user show.'
        ];
    }

    public function defineProperties() {
        return [
            'user_id' => [
                'title'             => 'User ID',
                'description'       => 'ID of the User',
                'default'           => 0,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Enter a valid number'
            ]
        ];
    }

    public function init() {
        $this->userId = $this->property('user_id');

        $userRepository = new UserRepository();

        $this->investor = $userRepository->findById($this->userId)->investor;
    }

}