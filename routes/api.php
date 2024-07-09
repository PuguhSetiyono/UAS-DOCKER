<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HotelRoomController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
    
    Route::post('/login', [LoginController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
    Route::post('/forgot-password', [LoginController::class, 'forgotPassword']);
    Route::post('/reset-password', [LoginController::class, 'resetPassword']);

    
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('hotel-rooms', HotelRoomController::class);
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/hotel-rooms', [HotelRoomController::class, 'index']);
        Route::post('/hotel-rooms', [HotelRoomController::class, 'store']);
        Route::get('/hotel-rooms/{id}', [HotelRoomController::class, 'show']);
        Route::patch('/hotel-rooms/{id}', [HotelRoomController::class, 'update']);
        Route::delete('/hotel-rooms/{id}', [HotelRoomController::class, 'destroy']);
    });

   // Route::prefix('hotel-rooms')->group(function () {
      //  Route::patch('/{id}', [HotelRoomController::class, 'update']);