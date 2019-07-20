<?php


Route::group(['prefix' => 'api/v1'], function () {
//    Route::resource('***', 'Denora\Duebus\Http\Sectors');

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('sector', 'Denora\Duebus\Http\SectorController');
        }
    );

});
