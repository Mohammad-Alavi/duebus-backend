<?php namespace Denora\Duebusverification\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Model
 */
class EntrepreneurVerification extends Model {

    use Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_duebusverification_entrepreneurverification';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'entrepreneur' => ['Denora\Duebusprofile\Models\Entrepreneur'],
    ];

    public $hasOne = [
        'id_of_managing_partner' => 'Denora\Duebusverification\Models\ManagingPartnerID',
        'article_of_association' => 'Denora\Duebusverification\Models\AssociationArticle',
        'commercial_license' => 'Denora\Duebusverification\Models\CommercialLicense',
        'chamber_of_commerce' => 'Denora\Duebusverification\Models\ChamberOfCommerce',
    ];

    /**
     * @return bool
     */
    public function getIsVerifiedAttribute(){
        return
            $this->id_of_managing_partner->details->is_verified &&
            $this->article_of_association->details->is_verified &&
            $this->commercial_license->details->is_verified &&
            $this->chamber_of_commerce->details->is_verified;
    }

}
