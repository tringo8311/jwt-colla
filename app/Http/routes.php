<?php
// app/Http/routes.php

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'v1/password'], function() {
    Route::get('email', 'Auth\PasswordController@getEmail');
    Route::post('email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::get('reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('reset', 'Auth\PasswordController@postReset');
});

Route::group(['prefix' => 'v1/api'], function()
{
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::post('refresh', 'AuthenticateController@refresh');

    //Route::resource('users/signup', 'UserController', ['only' => ['index']]);
    Route::post('user/signup', 'UserController@signup');

    Route::get('store/near', 'StoreController@near');
    Route::get('store/{id}', 'StoreController@show');
    Route::post('store/{id}', 'StoreController@update');
    Route::get('store/{id}/offers', 'StoreController@fetch_offers');
    Route::get('store/{id}/customers', 'StoreController@fetch_customers');
    Route::get('store/show/{id}', 'StoreController@show');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile/{id}', 'ProfileController@update');
    Route::get('profile/{id}/place', 'ProfileController@place');
    Route::post('profile/{id}/favourite', 'ProfileController@favourite');
    Route::post('profile/{id}/contact', 'ProfileController@contact');

    Route::resource('profile.feedbacks', 'ProfileFeedbackController');
    Route::resource('profile.notes', 'ProfileNoteController');
    Route::resource('profile.reservations', 'ProfileReservationController');

    Route::resource('store_offer', 'OfferController');
    Route::resource('owner', 'OwnerController');
    Route::resource('owner.reservations', 'OwnerReservationController');
    Route::put('owner/{user_id}/reservations/{id}/answer', 'OwnerReservationController@answer');
});