<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('spark::welcome');
});

Route::get('home', ['middleware' => 'auth', function () {
    return view('home');
}]);


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes dictate where the API endpoints are. All API endpoints
| are versioned and should be documented in their respective version
| documentation.
|
*/
Route::group(['namespace' => 'Api', 'prefix' => 'api'], function ()
{
    Route::group(['namespace' => 'VersionOne', 'prefix' => 'v1'], function()
    {
        Route::get('/', 'DocumentationController@get');

        Route::post('token', 'AuthenticationController@getToken');
        Route::delete('token', 'AuthenticationController@destroyToken');

        Route::get('public_key', 'EncryptionController@getPublicKey');
        Route::delete('public_key', 'EncryptionController@destroyPublicKey');

        Route::post('sign_up', 'SignUpController@store');
    });
});