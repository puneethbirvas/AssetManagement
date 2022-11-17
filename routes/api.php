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
use App\Http\Controllers\AmcController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MaintenanceController;


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
Route::post('asset/assetId', [AssetController::class, 'assetId']);

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
Route::post('label/assetGetId', [LabelController::class, 'assetGetId']);

//ScrapAssetes
Route::post('scrapAsset/add',[ScrapAssetController::class,'store']);
Route::get('scrapAsset/showData',[ScrapAssetController::class,'showData']);

//Audit
Route::post('audit/add', [AuditController::class, 'store']);
Route::post('audit/{id}/update', [AuditController::class, 'update']);
Route::post('audit/{id}/delete', [AuditController::class, 'destroy']);
Route::get('audit/showData', [AuditController::class, 'showData']);
Route::post('audit/{id}/viewAuditReport', [AuditController::class, 'viewAuditReport']);

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

//Maintenance
Route::post('maintenance/add', [MaintenanceController::class, 'store']);

//Amc
Route::post('amc/add', [AmcController::class, 'store']);
Route::post('amc/{id}/update', [AmcController::class, 'update']);
Route::post('amc/{id}/delete', [AmcController::class, 'destroy']);
Route::get('amc/showData', [AmcController::class, 'showData']);
Route::get('amc/{id}/serviceDue', [AmcController::class, 'serviceDue']);
Route::get('amc/viewAmcRenewal', [AmcController::class, 'viewAmcRenewal']);



//Certificate
Route::post('certificate/add', [CertificateController::class, 'store']);
Route::post('certificate/{id}/update', [CertificateController::class, 'update']);
Route::post('certificate/{id}/delete', [CertificateController::class, 'destroy']);
Route::get('certificate/showData', [CertificateController::class, 'showData']);

//Warranty
Route::get('warranty/showData', [WarrantyController::class, 'showData']);
Route::get('warranty/viewAsset', [WarrantyController::class, 'viewAsset']);

//Insurence
Route::post('insurance/add', [InsuranceController::class, 'store']);
Route::post('insurance/{id}/update', [InsuranceController::class, 'update']);
Route::post('insurance/{id}/delete', [InsuranceController::class, 'destroy']);
Route::get('insurance/showData', [InsuranceController::class, 'showData']);
Route::get('insurance/{id}/insuranceDue', [InsuranceController::class, 'insuranceDue']);
Route::get('insurance/viewInsuranceRenewal', [InsuranceController::class, 'viewInsuranceRenewal']);
Route::post('insurance/{id}/renewalInsurance', [InsuranceController::class, 'renewalInsurance']);