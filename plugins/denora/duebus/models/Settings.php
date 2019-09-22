<?php namespace Denora\Duebus\Models;

use Model;

class Settings extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];
    // A unique code

    public $settingsCode = 'denora_duebus_settings';
    // Reference to field configuration

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'site_title'                 => 'required',
        'site_footer_title'          => 'required',
        'home_page_heading_author_1' => 'required',
        'home_page_heading_text_1'   => 'required',
        'home_page_heading_author_2' => 'required',
        'home_page_heading_text_2'   => 'required',
        'currency_symbol'            => 'required',
    ];

    public $attachOne = [
        'home_page_banner' => 'System\Models\File',
        'blog_page_banner' => 'System\Models\File',
        'logo'             => 'System\Models\File',
    ];

}
