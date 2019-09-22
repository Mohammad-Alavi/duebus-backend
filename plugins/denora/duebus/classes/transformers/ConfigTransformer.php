<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Classes\Repositories\SectorRepository;
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
            'site_title'        => $settings::get('site_title', self::$default['site_title']),
            'site_footer_title' => $settings::get('site_footer_title', self::$default['site_footer_title']),

            'home_page_heading_1_section' => [
                'author' => $settings::get('home_page_heading_author_1', self::$default['home_page_heading_1_section_author']),
                'text'   => $settings::get('home_page_heading_text_1', self::$default['home_page_heading_1_section_text']),
            ],
            'home_page_heading_2_section' => [
                'author' => $settings::get('home_page_heading_author_2', self::$default['home_page_heading_2_section_author']),
                'text'   => $settings::get('home_page_heading_text_2', self::$default['home_page_heading_2_section_text']),
            ],

            'currency_symbol' => $settings::get('currency_symbol', self::$default['currency_symbol']),

            'home_page_banner' => $settings->home_page_banner ? $settings->home_page_banner->path : null,
            'blog_page_banner' => $settings->blog_page_banner ? $settings->blog_page_banner->path : null,
            'logo'             => [
                'small'    => $settings->logo ? $settings->logo->getThumb(200, 0, ['mode' => 'auto']) : null,
                'medium'   => $settings->logo ? $settings->logo->getThumb(400, 0, ['mode' => 'auto']) : null,
                'original' => $settings->logo ? $settings->logo->path : null
            ],

            'businesses_count' => (new BusinessRepository)->countAll(),

            'sectors' => SectorsTransformer::transform((new SectorRepository())->findAll()),

            'about_us_sections' => $settings::get('about_us_sections')?: self::$default['about_us_sections'],
        ];

    }

    private static $default = [
        'site_title' => 'Duebus',
        'site_footer_title' => 'Duebus',

        'home_page_heading_1_section_author' => 'Bahaa Abou Zeid',
        'home_page_heading_1_section_text' => '“I’ve developed a new concept for a gym – if only I could find an experienced investor to get it started.”',
        'home_page_heading_2_section_author' => 'Nawaf Al Arbash',
        'home_page_heading_2_section_text' => '“We’re looking to invest in the sport industry’s freshest concepts.”',

        'currency_symbol' => 'KWD',

        'about_us_sections' => [
            [
                'section_title' => 'Our Story',
                'section_text' => 'He heard stories: Of Mergers and Men, Pride and Partnerships, and Companies Fall Apart. Some were told to him by their characters, while others were just tales circling around the business world. A lot of these business stories could have had different endings—or wouldn’t have ended at all—had there been more visibility and connections in the market. 

As a partner in several companies, including a business incubator, and working closely in the family business, our founder has talked with individuals across different sectors, each of whom told him their own account of the current state of business. The more he heard, the more often he pondered the concept of an online hub that would foster opportunity and provide its users with virtual guidance. It would be a safe space for people to post their businesses and business ideas anonymously in pursuit of goals like selling a company or investing in another business. No matter the business’s size or industry, this platform would be the reference destination for seeking potentially lucrative prospects with ease. 

Once a fleeting idea, today this platform is called DueBus.'
            ],
            [
                'section_title' => 'Mission',
                'section_text' => 'We connect Investors, Entrepreneurs, and their Representatives through an online hub that facilitates shared business opportunities.'
            ],
            [
                'section_title' => 'Vision',
                'section_text' => 'To conduct business deals in good trust online from virtual meet to sign.'
            ],
        ],

    ];
}
