<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'middleware' => 'auth.apikey', 'namespace' => 'Api'], function () {
    Route::get('car-check', 'CarRequestController@check')->name('index');
});

