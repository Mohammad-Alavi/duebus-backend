<?php namespace Denora\Duebusverification\Models;

use Model;

/**
 * Model
 */
class InvestorVerification extends Model {

    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusverification_investorverification';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'investor' => ['Denora\Duebusprofile\Models\Investor'],
    ];

    public $hasOne = [
        'passport' => 'Denora\Duebusverification\Models\Passport',
        'identification' => 'Denora\Duebusverification\Models\Identification'
    ];

    /**
     * @return bool
     */
    public function getIsVerifiedAttribute(){
        return $this->passport->details->is_verified && $this->identification->details->is_verified;
    }

}
