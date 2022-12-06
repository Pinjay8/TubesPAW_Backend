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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');


Route::group(['middleware' => 'auth:api'], function(){

    // BARANG
    Route::get('product', 'Api\ProductController@index');
    Route::get('product/{id}', 'Api\ProductController@show');
    Route::post('product', 'Api\ProductController@store');
    Route::put('product/{id}', 'Api\ProductController@update');
    Route::delete('product/{id}', 'Api\ProductController@destroy');

    // USER (dapatin User && update user)
    Route::get('getuser/{id}','Api\UserController@getuser');
    Route::put('updateprofile/{id}','Api\UserController@updateprofile');

    // WISHLIST
    Route::get('wishlist', 'Api\WishlistController@index');
    Route::get('wishlist/{id}', 'Api\WishlistController@show');
    Route::post('wishlist', 'Api\WishlistController@store');
    Route::put('wishlist/{id}', 'Api\WishlistController@update');
    Route::delete('wishlist/{id}', 'Api\WishlistController@destroy');
    Route::get('wishlist/{id}', 'Api\WishlistController@showbyuser');

    // TOKO
    Route::get('toko', 'Api\TokoController@index');
    Route::get('toko/{id}', 'Api\TokoController@show');
    Route::post('toko', 'Api\TokoController@store');
    Route::put('toko/{id}', 'Api\TokoController@update');
    Route::delete('toko/{id}', 'Api\TokoController@destroy');
    Route::get('toko/{id}', 'Api\TokoController@showbyuser');
});

Route::get('email/verify/{id}', 'Api\EmailVerificationController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'Api\EmailVerificationController@resend')->name('verificationapi.resend');