<?php

Route::group(['prefix' => 'api/v1'], function () {

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('profile/investor/verify', 'Denora\Duebusverification\Http\InvestorVerificationController');
        }
    );


});
