<?php namespace Denora\Duebusbusiness;

use System\Classes\PluginBase;

class Plugin extends PluginBase {

    public function registerComponents() {
    }

    public function registerSettings() {
    }

    public function registerReportWidgets()
    {
        return [
            'Denora\Duebusbusiness\ReportWidgets\BusinessStatistics' => [
                'label'   => 'Business Statistics',
                'context' => 'dashboard',
                'permissions' => [
                ],
            ],
            'Denora\Duebusbusiness\ReportWidgets\IndustryStatistics' => [
                'label'   => 'Industry Statistics',
                'context' => 'dashboard',
                'permissions' => [
                ],
            ],
        ];
    }

}
