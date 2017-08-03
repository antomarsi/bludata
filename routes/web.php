<?php

Route::get('/', function () {
    $state = config('app.state');
    return view('vue', compact('state'));
});

Auth::routes();
