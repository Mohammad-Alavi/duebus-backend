<?php namespace Denora\TapCompany;

use System\Classes\PluginBase;

class Plugin extends PluginBase {

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents() {
        return [
            'Denora\Tapcompany\Components\Pay'           => 'pay',
            'Denora\Tapcompany\Components\PaymentVerify' => 'paymentVerify',
        ];
    }

    /**
     * Registers any back-end configuration links used by this plugin.
     *
     * @return array
     */
    public function registerSettings() {
        return [
            'payment' => [
                'label'       => 'Payment',
                'description' => 'Settings related to payment system.',
                'category'    => 'Letter Writing',
                'icon'        => 'icon-money',
                'class'       => 'Denora\Tapcompany\Models\Settings',
                'order'       => 500,
                'keywords'    => 'pay payment tap money transaction'
            ],
        ];
    }
}
