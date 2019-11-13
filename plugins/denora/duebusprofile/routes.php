<?php

Route::group(['prefix' => 'api/v1'], function () {

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('user', 'Denora\Duebusprofile\Http\UserController');
            Route::resource('profile', 'Denora\Duebusprofile\Http\ProfileController');
            Route::resource('entrepreneur', 'Denora\Duebusprofile\Http\EntrepreneurController');
            Route::resource('investor', 'Denora\Duebusprofile\Http\InvestorController');
            Route::resource('representative', 'Denora\Duebusprofile\Http\RepresentativeController');
            Route::resource('analyze/entrepreneur', 'Denora\Duebusprofile\Http\AnalyzeEntrepreneurController');
        }
    );

});
