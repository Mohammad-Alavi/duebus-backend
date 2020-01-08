<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Classes\Repositories\SectorRepository;
use Denora\Duebus\Models\Settings;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;

class ConfigTransformer
{

    /**
     * @var array
     */
    private static $default = [
        'front_end_url' => 'https://duebus-3527.firebaseapp.com',

        'site_title' => 'Duebus',
        'site_footer_title' => 'Duebus',

        'home_page_heading_1_section_author' => 'Bahaa Abou Zeid',
        'home_page_heading_1_section_text' => '“I’ve developed a new concept for a gym – if only I could find an experienced investor to get it started.”',
        'home_page_heading_2_section_author' => 'Nawaf Al Arbash',
        'home_page_heading_2_section_text' => '“We’re looking to invest in the sport industry’s freshest concepts.”',

        'currency_symbol' => 'KWD',

        'about_us' => [
            'sections' => [
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
            'our_story' => [
                'title' => 'Our Story',
                'text' => 'He heard stories: Of Mergers and Men, Pride and Partnerships, and Companies Fall Apart. Some were told to him by their characters, while others were just tales circling around the business world. A lot of these business stories could have had different endings—or wouldn’t have ended at all—had there been more visibility and connections in the market. 

As a partner in several companies, including a business incubator, and working closely in the family business, our founder has talked with individuals across different sectors, each of whom told him their own account of the current state of business. The more he heard, the more often he pondered the concept of an online hub that would foster opportunity and provide its users with virtual guidance. It would be a safe space for people to post their businesses and business ideas anonymously in pursuit of goals like selling a company or investing in another business. No matter the business’s size or industry, this platform would be the reference destination for seeking potentially lucrative prospects with ease. 

Once a fleeting idea, today this platform is called DueBus.'
            ],
            'mission' => [
                'title' => 'Mission',
                'text' => 'We connect Investors, Entrepreneurs, and their Representatives through an online hub that facilitates shared business opportunities.'
            ],
            'vision' => [
                'title' => 'Vision',
                'text' => 'To conduct business deals in good trust online from virtual meet to sign.'
            ],
        ],
        'privacy_policy_text' => 'This is privacy policy text.',
        'range_of_investments' => [
            '< 10,000',
            '10,000 - 25,000',
            '25,000 - 50,000',
            '50,000+',
        ],
        'number_of_clients' => [
            'None',
            '1-3',
            '4-7',
            'More than 8',
        ],
        'number_of_businesses' => [
            'None',
            '1-3',
            '4-7',
            'More than 8',
        ],
        'interested_in' => [
            'Investing',
            'Funding/Selling a Business',
        ],
        'roles_in_business' => [
            'Founder',
            'Co-Founder',
            'Partner',
        ],
        'legal_structures' => [
            'Legal structure 1',
            'Legal structure 2',
            'Legal structure 3',
        ],
        'reasons_of_selling_equity' => [
            'Raise Capital',
            'Exit',
            'Strategic Requirement',
            'Legal',
            'Other',
        ],
        'revenue_ranges' => [
            [
                'from' => null,
                'to' => 1000
            ],
            [
                'from' => 1000,
                'to' => 2500
            ],
            [
                'from' => 2500,
                'to' => 5000
            ],
            [
                'from' => 5000,
                'to' => 10000
            ],
            [
                'from' => 10000,
                'to' => 25000
            ],
            [
                'from' => 25000,
                'to' => null
            ],
        ],

        'business_price_with_package' => 15,
        'business_price_with_no_package' => 20,
        'view_price_with_package' => 5,
        'view_price_with_no_package' => 10,
        'reveal_price_with_package' => 1,
        'reveal_price_with_no_package' => 5,
        'inquiry_price_with_package' => 3,
        'inquiry_price_with_no_package' => 5,
        'duebus_promotion_price' => 10,
        'industry_promotion_price' => 5,

        'hint' => 'This hint helps you enter the data'
    ];

    /**
     *
     * @return array
     */
    static function transform()
    {

        $settings = Settings::instance();

        return [
            'front_end_url' => $settings::get('front_end_url') ?: self::$default['front_end_url'],

            'site_title' => $settings::get('site_title') ?: self::$default['site_title'],
            'site_footer_title' => $settings::get('site_footer_title') ?: self::$default['site_footer_title'],

            'home_page_heading_1_section' => [
                'author' => $settings::get('home_page_heading_author_1') ?: self::$default['home_page_heading_1_section_author'],
                'text' => $settings::get('home_page_heading_text_1') ?: self::$default['home_page_heading_1_section_text'],
            ],
            'home_page_heading_2_section' => [
                'author' => $settings::get('home_page_heading_author_2') ?: self::$default['home_page_heading_2_section_author'],
                'text' => $settings::get('home_page_heading_text_2') ?: self::$default['home_page_heading_2_section_text'],
            ],

            'currency_symbol' => $settings::get('currency_symbol') ?: self::$default['currency_symbol'],

            'home_page_banner' => $settings->home_page_banner ? $settings->home_page_banner->path : null,
            'blog_page_banner' => $settings->blog_page_banner ? $settings->blog_page_banner->path : null,
            'logo' => [
                'small' => $settings->logo ? $settings->logo->getThumb(200, 0, ['mode' => 'auto']) : null,
                'medium' => $settings->logo ? $settings->logo->getThumb(400, 0, ['mode' => 'auto']) : null,
                'original' => $settings->logo ? $settings->logo->path : null
            ],

            'businesses_count' => (new BusinessRepository)->countAll(),

            'about_us' => [
                'sections' =>  $settings::get('about_us_repeater') ?: self::$default['about_us']['sections'],
                'our_story' => [
                    'title' => $settings::get('our_story_title') ?: self::$default['about_us']['our_story']['title'],
                    'text' => $settings::get('our_story_text') ?: self::$default['about_us']['our_story']['text'],
                ],
                'mission' => [
                    'title' => $settings::get('mission_title') ?: self::$default['about_us']['mission']['title'],
                    'text' => $settings::get('mission_text') ?: self::$default['about_us']['mission']['text'],
                ],
                'vision' => [
                    'title' => $settings::get('vision_title') ?: self::$default['about_us']['vision']['title'],
                    'text' => $settings::get('vision_text') ?: self::$default['about_us']['vision']['text'],
                ],
            ],

            'privacy_policy' => $settings::get('privacy_policy_text') ?: self::$default['privacy_policy_text'],

            'registration_fields' => [
                'sectors' => SectorsTransformer::transform((new SectorRepository())->findAll()),
                'range_of_investments' => self::getValues($settings::get('range_of_investments_repeater')) ?: self::$default['range_of_investments'],
                'number_of_clients' => self::getValues($settings::get('number_of_clients_repeater')) ?: self::$default['number_of_clients'],
                'number_of_businesses' => self::getValues($settings::get('number_of_businesses_repeater')) ?: self::$default['number_of_businesses'],
                'interested_in' => self::getValues($settings::get('interested_in_repeater')) ?: self::$default['interested_in'],
            ],

            'inquiry_titles' => [
                'investment proposal',
                'founder and shareholders',
                'latest operation performance',
                'assets',
                'liabilities',
                'legals',
            ],

            'business_fields' => [
                'roles' => self::getValues($settings::get('roles_in_business_repeater')) ?: self::$default['roles_in_business'],
                'legal_structures' => self::getValues($settings::get('legal_structures_repeater')) ?: self::$default['legal_structures'],
                'reasons_of_selling_equity' => self::getValues($settings::get('reasons_of_selling_equity_repeater')) ?: self::$default['reasons_of_selling_equity'],
                'revenue_ranges' => $settings::get('revenue_ranges_repeater') ?: self::$default['revenue_ranges'],
            ],
            'prices' => [
                'business_price_with_package' => (int)$settings::get('business_price_with_package') ?: self::$default['business_price_with_package'],
                'business_price_with_no_package' => (int)$settings::get('business_price_with_no_package') ?: self::$default['business_price_with_no_package'],
                'view_price_with_package' => (int)$settings::get('view_price_with_package') ?: self::$default['view_price_with_package'],
                'view_price_with_no_package' => (int)$settings::get('view_price_with_no_package') ?: self::$default['view_price_with_no_package'],
                'reveal_price_with_package' => (int)$settings::get('reveal_price_with_package') ?: self::$default['reveal_price_with_package'],
                'reveal_price_with_no_package' => (int)$settings::get('reveal_price_with_no_package') ?: self::$default['reveal_price_with_no_package'],
                'inquiry_price_with_package' => (int)$settings::get('inquiry_price_with_package') ?: self::$default['inquiry_price_with_package'],
                'inquiry_price_with_no_package' => (int)$settings::get('inquiry_price_with_no_package') ?: self::$default['inquiry_price_with_no_package'],
                'duebus_promotion_price' => (int)$settings::get('duebus_promotion_price') ?: self::$default['duebus_promotion_price'],
                'industry_promotion_price' => (int)$settings::get('industry_promotion_price') ?: self::$default['industry_promotion_price'],
            ],
            'hints' => [
                'become_investor' => [
                    'interested_in_sectors' => $settings::get('hint_investor_interested_in_sectors') ?: self::$default['hint'],
                    'range_of_investment' => $settings::get('hint_investor_range_of_investment') ?: self::$default['hint'],
                    'business_invested_in' => $settings::get('hint_businesses_invested_in') ?: self::$default['hint'],
                ],
                'become_entrepreneur' => [
                    'experience' => $settings::get('hint_experience') ?: self::$default['hint'],
                    'education' => $settings::get('hint_education') ?: self::$default['hint'],
                ],
                'become_representative' => [
                    'interested_in_sectors' => $settings::get('hint_representative_interested_in_sectors') ?: self::$default['hint'],
                    'client_represent' => $settings::get('hint_client_represent') ?: self::$default['hint'],
                    'interested_in' => $settings::get('hint_interested_in') ?: self::$default['hint'],
                    'range_of_investment' => $settings::get('hint_representative_range_of_investment') ?: self::$default['hint'],
                ],
                'add_business' => [
                    'business_name' => $settings::get('hint_business_name') ?: self::$default['hint'],
                    'industry' => $settings::get('hint_industry') ?: self::$default['hint'],
                    'year_founded' => $settings::get('hint_year_founded') ?: self::$default['hint'],
                    'website' => $settings::get('hint_website') ?: self::$default['hint'],
                    'social_media' => $settings::get('hint_social_media') ?: self::$default['hint'],
                    'allow_reveal_name' => $settings::get('hint_allow_reveal_name') ?: self::$default['hint'],
                    'equity_holders' => $settings::get('hint_equity_holders') ?: self::$default['hint'],
                    'business_value' => $settings::get('hint_business_value') ?: self::$default['hint'],
                    'equity_for_sale' => $settings::get('hint_equity_for_sale') ?: self::$default['hint'],
                    'legal_proceedings' => $settings::get('hint_legal_proceedings') ?: self::$default['hint'],
                    'concern_with_business_employees' => $settings::get('hint_concern_with_business_employees') ?: self::$default['hint'],
                    'inequity_holder_in_debt' => $settings::get('hint_inequity_holder_in_debt') ?: self::$default['hint'],
                    'latest_operation_performance' => [
                        'revenue' => $settings::get('hint_revenue') ?: self::$default['hint'],
                        'cogs' => $settings::get('hint_cogs') ?: self::$default['hint'],
                        'salaries' => $settings::get('hint_salaries') ?: self::$default['hint'],
                        'operating_expenses' => $settings::get('hint_operating_expenses') ?: self::$default['hint'],
                        'ebitda' => $settings::get('hint_ebitda') ?: self::$default['hint'],
                        'ebit' => $settings::get('hint_ebit') ?: self::$default['hint'],
                        'net_profit' => $settings::get('hint_net_profit') ?: self::$default['hint'],
                    ],
                    'assets' => [
                        'cash_and_equivalents' => $settings::get('hint_cash_and_equivalents') ?: self::$default['hint'],
                        'account_receivable' => $settings::get('hint_account_receivable') ?: self::$default['hint'],
                        'inventory' => $settings::get('hint_inventory') ?: self::$default['hint'],
                        'tangible_assets' => $settings::get('hint_tangible_assets') ?: self::$default['hint'],
                        'intangible_assets' => $settings::get('hint_intangible_assets') ?: self::$default['hint'],
                        'financial_assets' => $settings::get('hint_financial_assets') ?: self::$default['hint'],
                    ],
                    'liabilities' => [
                        'account_payable' => $settings::get('hint_account_payable') ?: self::$default['hint'],
                        'other_current_liabilities' => $settings::get('hint_other_current_liabilities') ?: self::$default['hint'],
                        'long_term_liabilities' => $settings::get('hint_long_term_liabilities') ?: self::$default['hint'],
                        'equity' => $settings::get('hint_equity') ?: self::$default['hint'],
                    ],
                ],
            ],
        ];

    }

    private static function getValues($array)
    {
        if (!$array) return [];
        $values = [];
        foreach ($array as $item) {
            array_push($values, array_values($item)[0]);
        }

        return $values;
    }
}
