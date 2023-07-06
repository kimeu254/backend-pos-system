<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\People\CustomerController;
use App\Http\Controllers\People\SupplierController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\BarcodeTypeController;
use App\Http\Controllers\Setting\ProfileController;
use App\Http\Controllers\Setting\TaxController;
use App\Http\Controllers\Setting\UnitController;
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

    // Tax
    Route::controller(TaxController::class)->group(function () {
        Route::get('/taxes/all','allData');
        Route::get('/taxes','index');
        Route::get('/taxes/search','search');
        Route::get('/tax/{id}','show');
        Route::put('/tax/edit/{id}','update');
        Route::delete('/tax/remove/{id}','destroy');
    });

    // Unit
    Route::controller(UnitController::class)->group(function () {
        Route::get('/units/all','allData');
        Route::get('/units','index');
        Route::get('/units/search','search');
        Route::get('/unit/{id}','show');
        Route::put('/unit/edit/{id}','update');
        Route::delete('/unit/remove/{id}','destroy');
    });

    // Barcode Type
    Route::controller(BarcodeTypeController::class)->group(function () {
        Route::get('/barcode-types/all','allData');
        Route::get('/barcode-types','index');
        Route::get('/barcode-types/search','search');
        Route::get('/barcode-type/{id}','show');
        Route::put('/barcode-type/edit/{id}','update');
        Route::delete('/barcode-type/remove/{id}','destroy');
    });

    // Customer
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customers/all','allData');
        Route::get('/customers','index');
        Route::get('/customers/search','search');
        Route::get('/customer/{id}','show');
        Route::put('/customer/edit/{id}','update');
        Route::delete('/customer/remove/{id}','destroy');
    });

    // Supplier
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/suppliers/all','allData');
        Route::get('/suppliers','index');
        Route::get('/suppliers/search','search');
        Route::get('/supplier/{id}','show');
        Route::put('/supplier/edit/{id}','update');
        Route::delete('/supplier/remove/{id}','destroy');
    });

    // Brand
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands/all','allData');
        Route::get('/brands','index');
        Route::get('/brands/search','search');
        Route::get('/brand/{id}','show');
        Route::put('/brand/edit/{id}','update');
        Route::delete('/brand/remove/{id}','destroy');
    });

    // Category
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/all','allData');
        Route::get('/categories','index');
        Route::get('/categories/search','search');
        Route::get('/category/{id}','show');
        Route::put('/category/edit/{id}','update');
        Route::delete('/category/remove/{id}','destroy');
    });
});
