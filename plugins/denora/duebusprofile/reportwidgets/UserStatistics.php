<?php namespace Denora\Duebusprofile\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;

class UserStatistics extends ReportWidgetBase
{
    /**
     * @var array A list of variables to pass to the page.
     */
    public $vars = [];
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function init()
    {
        parent::init();

        $this->userRepository = new UserRepository();

        $this->vars['title'] = 'User Statistics';
        $this->vars['all_users_count'] = $this->userRepository->countAllUsers();

        $this->vars['entrepreneur_count'] = $this->userRepository->countAllEntrepreneurs();
        $this->vars['investor_count'] = $this->userRepository->countAllInvestors();
        $this->vars['representative_count'] = $this->userRepository->countAllRepresentatives();
    }


    public function render()
    {
        return $this->makePartial('widget');
    }
}
