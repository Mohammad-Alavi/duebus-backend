<?php

Route::group(['prefix' => 'api/v1/inbox'], function () {

    Route::middleware(['jwt.auth'])->group(
        function () {
            Route::resource('session', 'Denora\Inbox\Http\SessionController');
            Route::resource('message', 'Denora\Inbox\Http\MessageController');
        }
    );

});
