<?php

Route::group(['prefix' => 'api/v1'], function () {
//    Route::resource('businesscontrollers', 'Denora\Duebusbusiness\Http\BusinessController');

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('business', 'Denora\Duebusbusiness\Http\BusinessController');
            Route::resource('business/view', 'Denora\Duebusbusiness\Http\ViewController');
            Route::resource('business/reveal', 'Denora\Duebusbusiness\Http\RevealController');
        }
    );

});
