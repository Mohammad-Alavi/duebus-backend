<?php namespace Denora\TapCompany\Models;

use Backend\Controllers\Auth;
use Carbon\Carbon;
use Denora\Duebus\Classes\Repositories\PackageRepository;
use Denora\Duebus\Classes\Transformers\ConfigTransformer;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Notification\Classes\Events\WalletChargedEvent;
use Model;
use RainLab\User\Models\User;

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

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];

    function capture(){
        $this->paid_at = Carbon::now();
        $this->save();

        $this->onCaptured();
    }

    private function onCaptured(){
        $userRepository = new UserRepository();

        $businessRepository = new BusinessRepository();

        switch ($this->chargeable) {
            case 'wallet':{
                $user = $userRepository->findById($this->chargeable_id);
                $userRepository->chargeWallet($user->id, $this->points);
                break;
            }
            case 'business':{
                $price = ConfigTransformer::transform()['prices']['business_price_with_no_package'];
                $businessRepository->payBusiness($this->chargeable_id, $price);
                break;
            }
            case 'view':{
                $price = ConfigTransformer::transform()['prices']['view_price_with_no_package'];
                $businessRepository->viewBusiness($this->user->investor, $this->chargeable_id, $price);
                break;
            }
            case 'reveal':{
                $price = ConfigTransformer::transform()['prices']['reveal_price_with_no_package'];
                $businessRepository->revealBusiness($this->user->investor, $this->chargeable_id, $price);
                break;
            }
        }
    }

}
