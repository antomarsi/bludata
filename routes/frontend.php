<?php
Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('/user', function () {
        return ['data' => auth()->user()];
    });
    Route::get('users', 'UsersController@index');
    Route::post('users', 'UsersController@store');
    Route::put('users/{user}', 'UsersController@update');
    Route::delete('users/{user}', 'UsersController@destroy');


    Route::get('person', 'PersonController@index');
    Route::post('person', 'PersonController@store');
    Route::put('person/{person}', 'PersonController@update');
    Route::delete('person/{person}', 'PersonController@destroy');
});
