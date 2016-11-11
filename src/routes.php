<?php

Route::group(['middleware' => 'web'], function () {
    Route::get('terms', 'LaravelTreats\\Controller\\TermsController@getIndex');
    Route::get('privacy', 'LaravelTreats\\Controller\\TermsController@getPrivacy');
});
