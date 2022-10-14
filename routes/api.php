<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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

Route::middleware(['auth:sanctum'])->group(function () {
    //All secure URL's
    
   
          
    });

Route::post('user/add',[UsersController::class,'add']);

Route::post('user/{id}/update',[UsersController::class,'update']);

Route::post('user/{id}/delete',[UsersController::class,'destroy']);

Route::post('user/login',[UsersController::class,'loginUser']);

Route::post('user/{id}/block',[UsersController::class,'block']);

Route::post('user/showdata',[UsersController::class,'ShowData']);



