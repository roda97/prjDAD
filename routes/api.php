<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




//auth
//Route::post('login', 'LoginControllerAPI@login')->name('login');
//Route::post('register', 'AuthControllerAPI@register')->name('register');

///users
Route::get('users', 'UserControllerAPI@index');

//movements
//Route::post('movements', 'MovementControllerAPI@index');
#------ Users
Route::middleware('auth:api')->get('users', 'UserControllerAPI@index');
Route::post('users/register', 'UserControllerAPI@store');
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');
Route::middleware('auth:api')->put('users/{id}', 'UserControllerAPI@update');
Route::middleware('auth:api')->patch('users/password', 'UserControllerAPI@alterarPassword');

Route::get('home', 'WalletControllerAPI@countWallets');
//Route::post('register', 'UserControllerAPI@store');

Route::get('movements', 'MovementControllerAPI@index');
Route::post('movements', 'MovementControllerAPI@store');
Route::put('movements/{id}', 'MovementControllerAPI@update');
Route::post('movements/credit', 'MovementControllerAPI@addCredit');
Route::post('movements/debit', 'MovementControllerAPI@addDebit');
Route::post('movements/filter', 'MovementControllerAPI@index');

//wallets
Route::get('wallets', 'WalletControllerAPI@index');



#------ Passport
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

/*Route::middleware('auth:api')->get('teste', function () {
    return response()->json(['msg'=>'Só um teste'], 200);
});*/


