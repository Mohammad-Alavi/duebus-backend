<?php namespace Denora\TapCompany\Models;

use Model;

class Settings extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];
    // A unique code

    public $settingsCode = 'denora_tapcompany_settings';
    // Reference to field configuration

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'secret_api_key' => 'required',
    ];

}
