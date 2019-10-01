<?php

Route::group(['prefix' => 'api/v1'], function () {
    Route::resource('post', 'Denora\Blog\Http\PostController');
    Route::resource('category', 'Denora\Blog\Http\CategoryController');
});
