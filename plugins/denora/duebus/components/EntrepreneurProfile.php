<?php namespace Denora\Duebus\Components;

use Cms\Classes\ComponentBase;
use Denora\Duebus\Models\Entrepreneur;
use RainLab\User\Models\User;

class EntrepreneurProfile extends ComponentBase {

    /**
     * @var int
     */
    public $userId;

    /**
     * @var Entrepreneur
     */
    public $entrepreneur;

    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails() {
        return [
            'name'        => 'Entrepreneur Profile',
            'description' => 'A section where entrepreneur fields of a user show.'
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

        $this->entrepreneur = User::find($this->userId)->entrepreneur;
    }

}