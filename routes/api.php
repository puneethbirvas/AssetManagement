<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorTypeController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('block', function(){

    $response = [
        "message"=> "unable to access token expired"
    ];
    return response($response,401);
})->name('block');

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

     
Route::middleware('auth:sanctum')->group( function () {
    Route::get('getData', [DepartmentController::class, 'index']);
});


//vendorApi
Route::post('vendor/add', [VendorController::class, 'store']);
Route::post('vendor/{id}/update', [VendorController::class, 'update']);
Route::post('vendor/{id}/delete', [VendorController::class, 'destroy']);
Route::post('vendor/showData', [VendorController::class, 'showData']);
 
//departmentContoller
// Route::post('register', [DepartmentController::class, 'register']);
Route::post('signIn', [DepartmentController::class, 'signIn']);
Route::post('upload', [DepartmentController::class, 'upload']);
Route::post('uploadimage', [DepartmentController::class, 'uploadimage']);
Route::post('storeimage', [DepartmentController::class, 'storeimage']);
Route::get('show', [DepartmentController::class, 'show']);
// Route::get('showData', [DepartmentController::class, 'userData']);


//VendorTypeController
Route::post('vendorType/add', [VendorTypeController::class, 'store']);
Route::post('vendorType/showData', [VendorTypeController::class, 'showData']);