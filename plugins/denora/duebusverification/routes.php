<?php

Route::group(['prefix' => 'api/v1'], function () {

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('verification/investor', 'Denora\Duebusverification\Http\InvestorVerificationController');
            Route::resource('verification/business', 'Denora\Duebusverification\Http\BusinessVerificationController');
        }
    );


});
