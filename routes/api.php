<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::prefix('auth')->group(function () {
    Route::post('login', 'API\AuthController@login');
    //Route::post('register', 'API\AuthController@register');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('user', 'API\AuthController@user');
        Route::post('logout', 'API\AuthController@logout');
    });
});

Route::middleware('auth:api')->group( function () {    
    //Usuarios
    Route::prefix('usuarios')->group(function () {
        Route::get('', 'API\UserController@index');
        Route::post('', 'API\UserController@store');
        Route::put('/{id}', 'API\UserController@update');
    });
    
    // Papeis
    Route::prefix('papeis')->group(function () {
        Route::get('', 'API\RoleController@index');
        Route::post('', 'API\RoleController@store');
        Route::put('/{id}', 'API\RoleController@update');
    });
});
