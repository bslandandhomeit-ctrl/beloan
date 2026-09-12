<?php

Route::group(['middleware'=>'auth', 'prefix' => 'traccar'], function() {

    Route::get('users', [
        'as'=>'traccar.users',
        'uses'=>'Controllers\Traccar\UserController@index'
    ]);

    Route::get('devices', [
        'as'=>'traccar.devices',
        'uses'=>'Controllers\Traccar\DevicesController@index'
    ]);
});