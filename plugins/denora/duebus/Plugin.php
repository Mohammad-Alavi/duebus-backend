<?php namespace Denora\Duebus;

use System\Classes\PluginBase;

class Plugin extends PluginBase {

    public $require = ['RainLab.Blog'];


    public function registerComponents() {
    }

    public function registerSettings() {
        return [
            'ui-config' => [
                'label'       => 'UI Configurations',
                'description' => 'Configurations of front-end website.',
                'category'    => 'DueBus',
                'icon'        => 'icon-code',
                'class'       => 'Denora\Duebus\Models\Settings',
                'order'       => 500,
                'keywords'    => 'settings front ui config configuration'
            ],
        ];
    }

}
