<?php namespace Denora\Duebusbusiness;

use RainLab\User\Models\User as UserModel;
use System\Classes\PluginBase;

class Plugin extends PluginBase {

    public function registerComponents() {
    }

    public function registerSettings() {
    }

    public function boot() {
        parent::boot();

        //  Relate user to [business] objects
        UserModel::extend(function ($model) {
            $model->hasMany['businesses'] = ['Denora\Duebusbusiness\Models\Business'];
        });
    }

}
