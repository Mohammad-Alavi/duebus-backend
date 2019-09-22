<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Settings;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;

class ConfigTransformer {

    /**
     *
     * @param Settings $settings
     *
     * @return array
     */
    static function transform($settings) {

        return [
            'site_title'        => $settings::get('site_title', 'Duebus'),
            'site_footer_title' => $settings::get('site_footer_title', 'Footer title comes here'),

            'home_page_heading_1_section' => [
                'author' => $settings::get('home_page_heading_author_1', 'Author 1'),
                'text'   => $settings::get('home_page_heading_text_1', 'Text 1 comes here ...'),
            ],
            'home_page_heading_2_section' => [
                'author' => $settings::get('home_page_heading_author_2', 'Author 2'),
                'text'   => $settings::get('home_page_heading_text_2', 'Text 2 comes here ...'),
            ],

            'currency_symbol' => $settings::get('currency_symbol', '$'),

            'home_page_banner' => $settings->home_page_banner ? $settings->home_page_banner->path : null,
            'blog_page_banner' => $settings->blog_page_banner ? $settings->blog_page_banner->path : null,
            'logo'             => [
                'small'    => $settings->logo ? $settings->logo->getThumb(200, 0, ['mode' => 'auto']) : null,
                'medium'   => $settings->logo ? $settings->logo->getThumb(400, 0, ['mode' => 'auto']) : null,
                'original' => $settings->logo ? $settings->logo->path : null
            ],

            'businesses_count' => (new BusinessRepository)->countAll(),
        ];

    }
}
