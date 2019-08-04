<?php

Route::group(['prefix' => 'api/v1'], function () {
//    Route::resource('businesscontrollers', 'Denora\Duebusbusiness\Http\BusinessController');

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('business', 'Denora\Duebusbusiness\Http\BusinessController');
        }
    );

});
