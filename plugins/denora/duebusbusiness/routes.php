<?php

use Denora\Duebusbusiness\Http\BusinessController;

Route::group(['prefix' => 'api/v1'], function () {

    Route::get('guest/business', function (){
        return (new BusinessController())->index();
    });


    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('business', 'Denora\Duebusbusiness\Http\BusinessController');
            Route::get('my/business', function (){
                return (new BusinessController())->myBusinesses();
            });
            Route::resource('business/view', 'Denora\Duebusbusiness\Http\ViewController');
            Route::resource('business/reveal', 'Denora\Duebusbusiness\Http\RevealController');
            Route::resource('bookmark', 'Denora\Duebusbusiness\Http\BookmarkController');
            Route::resource('promotion', 'Denora\Duebusbusiness\Http\PromotionController');
        }
    );

});
