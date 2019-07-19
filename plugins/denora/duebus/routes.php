<?php


Route::group(['prefix' => 'api/v1'], function () {
//    Route::resource('***', 'Denora\Duebus\Http\Sectors');

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('sector', 'Denora\Duebus\Http\SectorController');
            Route::resource('user', 'Denora\Duebus\Http\UserController');
            Route::resource('profile', 'Denora\Duebus\Http\ProfileController');
            Route::resource('profile/education', 'Denora\Duebus\Http\EducationController');
            Route::resource('profile/entrepreneur', 'Denora\Duebus\Http\EntrepreneurController');
            Route::resource('profile/investor', 'Denora\Duebus\Http\InvestorController');
            Route::resource('profile/representative', 'Denora\Duebus\Http\RepresentativeController');
        }
    );

});
