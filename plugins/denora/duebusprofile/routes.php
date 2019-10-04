<?php


Route::group(['prefix' => 'api/v1'], function () {
//    Route::resource('***', 'Denora\Duebus\Http\Sectors');

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('user', 'Denora\Duebusprofile\Http\UserController');
            Route::resource('profile', 'Denora\Duebusprofile\Http\ProfileController');
            Route::resource('profile/entrepreneur', 'Denora\Duebusprofile\Http\EntrepreneurController');
            Route::resource('profile/investor', 'Denora\Duebusprofile\Http\InvestorController');
            Route::resource('profile/representative', 'Denora\Duebusprofile\Http\RepresentativeController');
        }
    );

});
