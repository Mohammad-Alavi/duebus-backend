<?php namespace Denora\Duebusprofile;

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

        //  Relate user to [Investor, Entrepreneur, Representative] objects
        UserModel::extend(function ($model) {
            $model->hasOne['investor'] = ['Denora\Duebusprofile\Models\Investor'];
            $model->hasOne['entrepreneur'] = ['Denora\Duebusprofile\Models\Entrepreneur'];
            $model->hasOne['representative'] = ['Denora\Duebusprofile\Models\Representative'];
        });

        //  Add methods to user
        UserModel::extend(function($model) {
            $model->addDynamicMethod('decreasePoints', function($point, $description = '') use ($model) {
                if ($point > $model->point) return false;
                $model->point = $model->point - $point;
                $model->save();
                return true;
            });
        });
    }

}
