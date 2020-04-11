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

        $industries = array_column(ConfigTransformer::transform()['business_fields']['industries'], 'label');

        $this->vars['businesses_data_in_html'] = $this->getBusinessesDataInHtml($industries);
    }

    public function getBusinessesDataInHtml($industries)
    {
        $businessesDataInHtml = '<ul>';

        foreach ($industries as $industry) {
            $count = $this->businessRepository->countAll($industry);
            if ($count < 1) continue;
            $businessesDataInHtml .= '<li>' . $industry . '<span>' . $count . '</span></li>';
        }
        $businessesDataInHtml .= '</ul>';

        return $businessesDataInHtml;
    }

    public function render()
    {
        return $this->makePartial('widget');
    }
}
