<?php namespace Denora\Duebusbusiness\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;

class BusinessStatistics extends ReportWidgetBase
{
    /**
     * @var array A list of variables to pass to the page.
     */
    public $vars = [];
    /**
     * @var BusinessRepository
     */
    protected $businessRepository;

    public function init()
    {
        parent::init();

        $this->businessRepository = new BusinessRepository();

        $this->vars['title'] = 'Business Statistics';
        $this->vars['all_businesses_count'] = $this->businessRepository->countAll();
        $this->vars['published_businesses_count'] = $this->businessRepository->countAll(null, true);
        $this->vars['unpublished_businesses_count'] = $this->businessRepository->countAll(null, false);
    }

    public function render()
    {
        return $this->makePartial('widget');
    }
}
