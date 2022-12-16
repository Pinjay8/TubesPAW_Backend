<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;

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
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

//Resend the link to verify
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/success', function () {
    return view('mail');
});


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


    // Pengiriman
    Route::get('delivery', 'Api\DeliveryController@index');
    Route::get('delivery/{id}', 'Api\DeliveryController@show');
    Route::post('delivery', 'Api\DeliveryController@store');
    Route::put('delivery/{id}', 'Api\DeliveryController@update');
    Route::delete('delivery/{id}', 'Api\DeliveryController@destroy');
    

    // TOKO
    Route::get('toko', 'Api\TokoController@index');
    Route::get('toko/{id}', 'Api\TokoController@show');
    Route::post('toko', 'Api\TokoController@store');
    Route::put('toko/{id}', 'Api\TokoController@update');
    Route::delete('toko/{id}', 'Api\TokoController@destroy');
    Route::get('toko/{id}', 'Api\TokoController@showbyuser');

    Route::get('logout', 'Api\AuthController@logout');
});

// Route::get('email/verify/{id}', 'Api\EmailVerificationController@verify')->name('verificationapi.verify');
// Route::get('email/resend', 'Api\EmailVerificationController@resend')->name('verificationapi.resend');

