<?php


Route::group(['prefix' => 'api/v1'], function () {

    Route::resource('config', 'Denora\Duebus\Http\ConfigController');
    Route::resource('package', 'Denora\Duebus\Http\PackageController');

    Route::middleware(['jwt.auth'])->group(
        function () {
//            Route::resource('sector', 'Denora\Duebus\Http\SectorController');
        }
    );

});
