<?php namespace Denora\Duebusprofile;

use Carbon\Carbon;
use RainLab\User\Models\User as UserModel;
use System\Classes\PluginBase;

class Plugin extends PluginBase {

    public $require = ['RainLab.User', 'Rluders.Jwtauth', 'Mohsin.Rest'];

    public function registerComponents() {
    }

    public function registerSettings() {
    }

    public function boot() {
        parent::boot();

        //  Relate user to [Investor, Entrepreneur, Representative, Transaction, Bookmarked Businesses] objects
        UserModel::extend(function ($model) {
            $model->addDateAttribute('point_expires_at');

            $model->hasOne['investor'] = ['Denora\Duebusprofile\Models\Investor'];
            $model->hasOne['entrepreneur'] = ['Denora\Duebusprofile\Models\Entrepreneur'];
            $model->hasOne['representative'] = ['Denora\Duebusprofile\Models\Representative'];

            $model->hasOne['transaction'] = ['Denora\Tapcompany\Models\Transaction'];

            $model->belongsToMany['bookmarked_businesses'] = [
                'Denora\Duebusbusiness\Models\Business',
                'table'    => 'denora_duebus_user_bookmark',
                'key'      => 'user_id',
                'otherKey' => 'business_id',
                'timestamps' => true,
            ];
        });

        //  Add methods to user
        UserModel::extend(function($model) {
            $model->addDynamicMethod('getPointsAttribute', function() use ($model) {
                if (Carbon::now()->gt($model->point_expires_at)){
                    $model->point = 0;
                    $model->save();
                }
                return (int)$model->point;
            });
            $model->addDynamicMethod('decreasePoints', function($points, $description = '') use ($model) {
                if ($points > $model->points) return false;
                $model->point = $model->points - $points;
                $model->save();
                return true;
            });
        });
    }

}
