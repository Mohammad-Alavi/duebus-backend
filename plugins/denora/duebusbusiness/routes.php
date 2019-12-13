<?php

use Denora\Duebusbusiness\Http\BusinessController;
use Denora\Duebusbusiness\Http\PromotionController;

Route::group(['prefix' => 'api/v1'], function () {

    Route::get('guest/business', function (){
        return (new BusinessController())->index();
    });

    Route::get('guest/promotion', function (){
        return (new PromotionController())->index();
    });


    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('business', 'Denora\Duebusbusiness\Http\BusinessController');
            Route::get('my/business', function (){
                return (new BusinessController())->myBusinesses();
            });
            Route::resource('pay/business', 'Denora\Duebusbusiness\Http\PayBusinessController');
            Route::resource('view/business', 'Denora\Duebusbusiness\Http\ViewController');
            Route::resource('reveal/business', 'Denora\Duebusbusiness\Http\RevealController');
            Route::resource('bookmark/business', 'Denora\Duebusbusiness\Http\BookmarkController');
            Route::resource('promotion', 'Denora\Duebusbusiness\Http\PromotionController');
        }
    );

});
