<?php namespace Denora\Duebus\Classes\Transformers;

use Model;

class ConfigTransformer {

    /**
     *
     * @param Model $settings
     *
     * @return array
     */
    static function transform($settings) {

        return [
            'site_title'                  => $settings::get('site_title', 'Duebus'),
            'site_footer_title'           => $settings::get('site_footer_title', 'Footer title comes here'),
            'home_page_heading_1_section' => [
                'author' => $settings::get('home_page_heading_author_1', 'Author 1'),
                'text'   => $settings::get('home_page_heading_text_1', 'Text 1 comes here ...'),
            ],
            'home_page_heading_2_section' => [
                'author' => $settings::get('home_page_heading_author_2', 'Author 2'),
                'text'   => $settings::get('home_page_heading_text_2', 'Text 2 comes here ...'),
            ],
            'currency_symbol'             => $settings::get('currency_symbol', '$'),
        ];

    }
}
