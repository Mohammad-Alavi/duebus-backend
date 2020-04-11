<?php namespace Denora\TapCompany\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\TapCompany\Classes\Repositories\TransactionRepository;

class TransactionStatistics extends ReportWidgetBase
{
    /**
     * @var array A list of variables to pass to the page.
     */
    public $vars = [];
    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    public function init()
    {
        parent::init();

        $this->transactionRepository = new TransactionRepository();

        $this->vars['title'] = 'Transaction Statistics';
        $this->vars['all_transactions_count'] = $this->transactionRepository->countAll();
        $this->vars['paid_transactions_count'] = $this->transactionRepository->countAll(true);
        $this->vars['unpaid_transactions_count'] = $this->transactionRepository->countAll(false);
    }

    public function render()
    {
        return $this->makePartial('widget');
    }
}
