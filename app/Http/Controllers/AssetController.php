<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use DB;

class AssetController extends Controller
{
    public function store(Request $request)
    {
       try{                
            $asset = new Asset;
                
            $last= 'asset-'.$this->get();
            $asset->assetId = $last;
            $asset->department  = $request->department ;
            $asset->section = $request->section;
            $asset->assetName = $request->assetName;
            $asset->financialAssetId = $request->financialAssetId;
            $asset->vendorName = $request->vendorName;
            $asset->phoneNumber = $request->phoneNumber;
            $asset->email = $request->email;
            $asset->vendorAddress = $request->vendorAddress;
            $asset->assetType = $request->assetType;
            $asset->manufaturer = $request->manufaturer;
            $asset->assetModel = $request->assetModel;
            $asset->poNo = $request->poNo;
            $asset->invoiceNo = $request->invoiceNo;
            $asset->typeWarranty = $request->typeWarranty;

            if($asset->typeWarranty == 'warranty'){
            $asset->warrantyStartDate = $request->warrantyStartDate;
            $asset->warrantyEndDate = $request->warrantyEndDate; 
            //imageStoring
                if($file = $request->hasFile('warrantyDocument')) {
                    $file = $request->file('warrantyDocument') ;
                    $fileName = $file->getClientOriginalName() ;
                    $destinationPath = public_path().'/images/' ;
                    $file->move($destinationPath,$fileName);
                    $asset->warrantyDocument = '/images/'.$fileName ;
                }
            }
            if($asset->typeWarranty == 'noWarranty'){
                $asset->warrantyStartDate = null;
                $asset->warrantyEndDate = null; 
                $asset->warrantyDocument = null; 
            }
            if($file = $request->hasFile('uploadDocument')) {
                $file = $request->file('uploadDocument') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ;
                $file->move($destinationPath,$fileName);
                $asset->uploadDocument = '/images/'.$fileName ;
            }

            $asset->description = $request->description;

            if($file = $request->hasFile('assetImage')) {
                $file = $request->file('assetImage') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ;
                $file->move($destinationPath,$fileName);
                $asset->assetImage = '/images/'.$fileName ;
            }

            $asset->save();
            $response = [
                'success' => true,
                'message' => "successfully added",
                'status' => 201
            ];
            $status = 201;   
          
        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);        
    }

    //default asset-id
    public function get()
    {
        $last = DB::table('assets')->latest('id')->first();
        if(!$last){
           $user = "0";
        }else{
            $user = $last->id;
        }
        return $user  ;
    }
 

    //asset update
    public function update(Request $request, $id)
    {
    
        try{

            $asset = Asset::find($id);          
            if(!$asset){
                throw new Exception("asset not found");
            }
                
            // $asset->assetId = $request->assetId;
            $asset->Department  = $request->Department ;
            $asset->Section = $request->Section;
            $asset->assetName = $request->assetName;
            $asset->financialAssetId = $request->financialAssetId;
            $asset->vendorName = $request->vendorName;
            $asset->phoneNumber = $request->phoneNumber;
            $asset->email = $request->email;
            $asset->vendorAddress = $request->vendorAddress;
            $asset->assetType = $request->assetType;
            $asset->manufaturer = $request->manufaturer;
            $asset->assetModel = $request->assetModel;
            $asset->poNo = $request->poNo;
            $asset->invoiceNo = $request->invoiceNo;
            $asset->typeWarranty = $request->typeWarranty;

            if($asset->typeWarranty == 'warranty'){
            $asset->warrantyStartDate = $request->warrantyStartDate;
            $asset->warrantyEndDate = $request->warrantyEndDate; 
            //imageStoring
                if($file = $request->hasFile('warrantyDocument')) {
                    $file = $request->file('warrantyDocument') ;
                    $fileName = $file->getClientOriginalName() ;
                    $destinationPath = public_path().'/images/' ;
                    $file->move($destinationPath,$fileName);
                    $asset->warrantyDocument = '/images/'.$fileName ;
                }
            }
            if($asset->typeWarranty == 'noWarranty'){
                $asset->warrantyStartDate = null;
                $asset->warrantyEndDate = null; 
                $asset->warrantyDocument = null; 
            }
            if($file = $request->hasFile('uploadDocument')) {
                $file = $request->file('uploadDocument') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ;
                $file->move($destinationPath,$fileName);
                $asset->uploadDocument = '/images/'.$fileName ;
            }

            $asset->description = $request->description;

            if($file = $request->hasFile('assetImage')) {
                $file = $request->file('assetImage') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ;
                $file->move($destinationPath,$fileName);
                $asset->assetImage = '/images/'.$fileName ;
            }

            $asset->save();
            $response = [
                'success' => true,
                'message' => "details updated successfully",
                'status' => 201
            ];
            $status = 201;   
            

        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }
    
  
    //destroy
    public function destroy(Asset $asset, $id)
    {
        try{
            $asset = Asset::find($id);
            if(!$asset){
                throw new Exception("asset not found");
            }else{
                $asset->delete();
                $response = [
                    "message" => "asset deleted successfully",
                    "status" => 200
                ];
                $status = 200;                   
            }

        }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404;     
        }

        return response($response,$status);
    }  

    public function showData()
    {
      try{    
          $asset = DB::table('assets')->select('id','department','section','assetName','assetType','manufaturer','assetModel','warrantyStartDate','warrantyEndDate')->orderby('id','asc')->get();
          if(!$asset){
            throw new Exception("Asset not found");
          }
            $response=[
             "message" => "Asset List",
             "data" => $asset
              ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

}
