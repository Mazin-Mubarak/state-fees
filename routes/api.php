<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\UsersController;
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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('users', UsersController::class . '@create');
    Route::get('users', UsersController::class . '@getAll');
    Route::get('users/{id}', UsersController::class . '@show');
    Route::delete('users/{id}', UsersController::class . '@destroy');
    Route::put('users/{id}', UsersController::class . '@update');
    Route::post('logout', UsersController::class . '@logout');
});

Route::post('login', UsersController::class . '@login');

Route::post('services',ServicesController::class.'@create');
Route::get('services',ServicesController::class.'@getAll');
Route::get('services/{id}',ServicesController::class.'@show');
Route::delete('services/{id}',ServicesController::class.'@destroy');
Route::put('services/{id}',ServicesController::class.'@update');

Route::post('stores',StoresController::class.'@create');
Route::get('stores',StoresController::class.'@getAll');
Route::get('stores/{id}',StoresController::class.'@show');
Route::delete('stores/{id}',StoresController::class.'@destroy');
Route::put('stores/{id}',StoresController::class.'@update');

Route::post('payments',PaymentsController::class.'@create');
Route::get('payments',PaymentsController::class.'@getAll');
Route::get('payments/{id}',PaymentsController::class.'@show');
Route::delete('payments/{id}',PaymentsController::class.'@destroy');
Route::put('payments/{id}',PaymentsController::class.'@update');