<?php namespace Denora\TapCompany\Models;

use Backend\Controllers\Auth;
use Carbon\Carbon;
use Denora\Duebus\Classes\Repositories\PackageRepository;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Model;

/**
 * Model
 */
class Transaction extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    protected $dates = ['paid_at'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'denora_tapcompany_transactions';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    function capture(){
        $this->paid_at = Carbon::now();
        $this->save();

        $this->onCaptured();
    }

    private function onCaptured(){
        $user = Auth::user();

        $userRepository = new UserRepository();
        $businessRepository = new BusinessRepository();

        switch ($this->chargeable) {
            case 'wallet':{
                $user = $userRepository->findById($this->chargeable_id);
                $userRepository->chargeWallet($user->id, $this->points);
                break;
            }
            case 'business':{
                $businessRepository->publishBusiness($this->chargeable_id);
                break;
            }
            case 'view':{
                $businessRepository->viewBusiness($user->investor, $this->chargeable_id);
                break;
            }
            case 'reveal':{
                $businessRepository->revealBusiness($user->investor, $this->chargeable_id);
                break;
            }
        }
    }

}
