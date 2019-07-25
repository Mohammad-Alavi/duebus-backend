<?php

Route::group(['prefix' => 'api/v1'], function () {
    Route::resource('faq', 'Denora\Faq\Http\FaqController');
});
