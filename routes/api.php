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
use App\Http\Controllers\AuditController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AssetMasterController;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\TransferAssetController;
use App\Http\Controllers\TagAssetController;
use App\Http\Controllers\UntagAssetController;


Route::get('token', function() {
    $response = [          
        "message" =>  " Token is  required",
        "status" => 400
    ];
    $status = 400;

    return Response($response, $status);
                                                                                                           
})->name('token');


Route::middleware(['auth:sanctum'])->group(function () {
    //All secure URL's

    Route::post('user/{id}/update',[UsersController::class,'update']);
    Route::post('logout',[UsersController::class,'logout']);
    Route::post('user/{id}/delete',[UsersController::class,'destroy']);
    Route::post('user/{id}/block',[UsersController::class,'block']);
    Route::get('user/showData',[UsersController::class,'showData']);

    });

//users
Route::post('user/add',[UsersController::class,'store']);


//login 
Route::post('login',[UsersController::class,'loginUser']);


//vendor
Route::post('vendor/add', [VendorController::class, 'store']);
Route::post('vendor/{id}/update', [VendorController::class, 'update']);
Route::post('vendor/{id}/delete', [VendorController::class, 'destroy']);
Route::get('vendor/showData', [VendorController::class, 'showData']);

//VendorType
Route::post('vendorType/add', [VendorTypeController::class, 'store']);
Route::post('vendorType/{id}/update', [VendorTypeController::class, 'update']);
Route::post('vendorType/{id}/delete', [VendorTypeController::class, 'destroy']);
Route::get('vendorType/showData', [VendorTypeController::class, 'showData']);

//Asset
Route::post('asset/add', [AssetController::class, 'store']);
Route::post('asset/{id}/update', [AssetController::class, 'update']);
Route::post('asset/{id}/delete', [AssetController::class, 'destroy']);
Route::get('asset/showData', [AssetController::class, 'showData']);

//AssetType   
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
Route::post('section/add',[SectionController::class,'store']);
Route::post('section/{id}/update',[SectionController::class,'update']);
Route::post('section/{id}/delete',[SectionController::class,'destroy']);
Route::get('section/showData',[SectionController::class,'showData']);

//Label
Route::post('label/add',[LabelController::class,'store']);
Route::post('label/{id}/delete',[LabelController::class,'destroy']);
Route::get('label/showData',[LabelController::class,'showData']);

Route::get('label/{id}/getAssetName',[LabelController::class,'getAssetName']);


//ScrapAssetes
Route::post('scrapAsset/add',[ScrapAssetController::class,'store']);
Route::get('scrapAsset/showData',[ScrapAssetController::class,'showData']);

//Audit
Route::post('audit/add', [AuditController::class, 'store']);
Route::post('audit/{id}/update', [AuditController::class, 'update']);
Route::post('audit/{id}/delete', [AuditController::class, 'destroy']);
Route::get('audit/showData', [AuditController::class, 'showData']);
Route::get('audit/{id}/viewAuditReport', [AuditController::class, 'viewAuditReport']);

//Allocation
Route::post('allocation/add', [AllocationController::class, 'store']);
Route::post('allocation/{id}/update', [AllocationController::class, 'update']);
Route::get('allocation/showData', [AllocationController::class, 'showData']);
Route::get('alloation/{id}/getEmpName',[AllocationController::class, 'getEmpName']);

//TransferAsset
Route::post('transferAsset/{id}', [TransferAssetController::class, 'transferData']);

// AssetMaster
Route::get('assetMaster/{id}/showData', [AssetMasterController::class, 'showData']);

//GetData
Route::get('getDepartment', [GetDataController::class, 'getDepartment']);
Route::get('getSection/{id}', [GetDataController::class, 'getSection']);
Route::get('getAssetType/{id}', [GetDataController::class, 'getAssetType']);
Route::get('getAssetName/{id}', [GetDataController::class, 'getAssetName']);
Route::get('getVendor', [GetDataController::class, 'getVendor']);
Route::get('getVendorData/{id}', [GetDataController::class, 'getVendorData']);

//TagAsset
Route::post('tagAsset/add', [TagAssetController::class, 'store']);
Route::get('getAssetId/{id}', [TagAssetController::class, 'getAssetId']);
Route::get('selectAssetId', [TagAssetController::class, 'selectAssetId']);

//UnTagAsset
Route::post('untagAsset/add', [UntagAssetController::class, 'store']);
Route::get('untagAsset/showData', [UntagAssetController::class, 'showData']);