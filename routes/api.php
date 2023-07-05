<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/user/login',[AuthController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Logout
    Route::post('/user/logout',[AuthController::class,'logout']);

    // Profile
    Route::controller(ProfileController::class)->prefix('user/profile')->group(function () {
        Route::get('/{id}','user');
        Route::put('/update/{id}', 'update');
        Route::delete('/image/remove/{id}','destroyPicture');
    });

    // Account
    Route::controller(AccountController::class)->prefix('user/account')->group(function () {
        Route::put('/change-password/{id}','changePassword');
    });
});
