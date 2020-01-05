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

Route::put('/movements/email/{emailIncome}','MovementControllerAPI@send_reactivate_email');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//auth
//Route::post('login', 'LoginControllerAPI@login')->name('login');
//Route::post('register', 'AuthControllerAPI@register')->name('register');

///users
Route::get('users', 'UserControllerAPI@index');
Route::get('wallets', 'WalletControllerAPI@index');

#------ Users
Route::middleware('auth:api')->get('users', 'UserControllerAPI@index');
Route::post('users/newAccount', 'UserControllerAPI@store');
Route::middleware('auth:api')->post('users/OperatorAdmin', 'UserControllerAPI@storeOperatorAdmin'); 
Route::post('users/filter', 'UserControllerAPI@index'); 
Route::middleware('auth:api')->put('users/{id}', 'UserControllerAPI@update');  
Route::middleware('auth:api')->delete('users/destroy/{id}', 'UserControllerAPI@destroy');
Route::middleware('auth:api')->put('users/activate/{id}', 'UserControllerAPI@activateUser');
Route::middleware('auth:api')->patch('users/ProfilewithPass', 'UserControllerAPI@updateProfilewithPass');
Route::middleware('auth:api')->patch('users/ProfilewithoutPass', 'UserControllerAPI@updateProfilewithoutPass');
Route::middleware('auth:api')->get('users/profile', 'UserControllerAPI@profileRefresh');

//movements
//Route::post('movements', 'MovementControllerAPI@index');
Route::get('movements', 'MovementControllerAPI@index');
Route::post('movements', 'MovementControllerAPI@store');
Route::put('movements/{id}', 'MovementControllerAPI@update');
Route::post('movements/credit', 'MovementControllerAPI@addCredit');
Route::post('movements/debit', 'MovementControllerAPI@addDebit');
Route::post('movements/filter', 'MovementControllerAPI@index');
Route::get('movements/{id}/balance', 'MovementControllerAPI@getBalance');
Route::get('movements/stats/totals/{id}', 'MovementControllerAPI@getTotalsMovements');
Route::get('movements/stats/all', 'MovementControllerAPI@getAllMovements');

//wallets
Route::get('wallets', 'WalletControllerAPI@index');
Route::get('home', 'WalletControllerAPI@countWallets');
//Route::post('register', 'UserControllerAPI@store');


#------ Passport
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

/*Route::middleware('auth:api')->get('teste', function () {
    return response()->json(['msg'=>'Só um teste'], 200);
});*/


