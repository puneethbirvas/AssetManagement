<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorTypeController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AssettypeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScrapAssetController;



Route::middleware(['auth:sanctum'])->group(function () {
    //All secure URL's
           
    });

//users
Route::post('user/add',[UsersController::class,'store']);
Route::post('user/{id}/update',[UsersController::class,'update']);
Route::post('user/{id}/delete',[UsersController::class,'destroy']);
Route::post('user/login',[UsersController::class,'loginUser']);
Route::post('user/{id}/block',[UsersController::class,'block']);
Route::get('user/showData',[UsersController::class,'showData']);


//vendor
Route::post('vendor/add', [VendorController::class, 'store']);
Route::post('vendor/{id}/update', [VendorController::class, 'update']);
Route::post('vendor/{id}/delete', [VendorController::class, 'destroy']);
Route::get('vendor/showData', [VendorController::class, 'showData']);
 
//VendorType
Route::post('vendorType/add', [VendorTypeController::class, 'store']);
Route::get('vendorType/showData', [VendorTypeController::class, 'showData']);


//Asset
Route::post('asset/add', [AssetController::class, 'store']);
Route::post('asset/{id}/update', [AssetController::class, 'update']);
Route::post('asset/{id}/delete', [AssetController::class, 'destroy']);
Route::get('asset/showData', [AssetController::class, 'showData']);

//Asset_Type
Route::post('assetType/add',[AssettypeController::class,'store']);
Route::post('assetType/{id}/update',[AssettypeController::class,'update']);
Route::post('assetType/{id}/delete',[AssettypeController::class,'destroy']);
Route::get('assetType/showData',[AssettypeController::class,'showData']);

//Department
Route::post('department/add',[DepartmentController::class,'store']);
Route::post('department/{id}/update',[DepartmentController::class,'update']);
Route::post('department/{id}/delete',[DepartmentController::class,'destroy']);
Route::get('department/showData',[DepartmentController::class,'showData']);


//Section
Route::post('section/add',[SectionController::class,'add']);
Route::post('section/{id}/update',[SectionController::class,'update']);
Route::post('section/{id}/delete',[SectionController::class,'destroy']);
Route::get('section/showData',[SectionController::class,'showData']);

//Label
Route::post('Label/add',[LabelController::class,'store']);
Route::post('Label/{id}/delete',[LabelController::class,'destroy']);
Route::get('Label/showData',[LabelController::class,'showData']);

//ScrapAssetel
Route::post('scrapAsset/add',[ScrapAssetController::class,'store']);
Route::get('scrapAsset/showData',[ScrapAssetController::class,'showData']);