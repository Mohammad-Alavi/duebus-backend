<?php

Route::group(['prefix' => 'api/v1'], function () {
    Route::resource('capture', 'Denora\Tapcompany\Http\CaptureController');

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('notification', 'Denora\Notification\Http\NotificationController');
        }
    );

});
