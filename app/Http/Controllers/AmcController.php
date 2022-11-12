<?php

namespace App\Http\Controllers;

use App\Models\AMC;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Storage;
use Str;

class AMCController extends Controller
{

    public function store(Request $request)
    {
        try{
            $amc = new AMC;

            $amc->vendorName = $request->vendorName;
            $amc->periodFrom = $request->periodFrom;
            $amc->periodTo = $request->periodTo;
            $amc->premiumCost = $request->premiumCost;

            // Amc document
            $image = $request->amcDoc;
            if($image){ // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
            }
            
            $amc->amcDoc = $imagePath;
            $amc->servicePattern = $request->servicePattern;
            $amc->department = $request->department;
            $amc->section = $request->section;
            $amc->assetType = $request->assetType;
            $amc->assetName = $request->assetName;
        
            $amc->save();
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

    //update
    public function update(Request $request)
    {
        try{
            $amc = AMC::find($id);          
            if(!$amc){
                throw new Exception("AMC not found");
            }

            $amc->vendorName = $request->vendorName;
            $amc->periodFrom = $request->periodFrom;
            $amc->periodTo = $request->periodTo;
            $amc->premiumCost = $request->premiumCost;

            // Amc document
            $image = $request->amcDoc;
            if($image){ // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
            }
            
            $amc->amcDoc = $imagePath;
            $amc->servicePattern = $request->servicePattern;
            $amc->department = $request->department;
            $amc->section = $request->section;
            $amc->assetType = $request->assetType;
            $amc->assetName = $request->assetName;
        
            $amc->save();
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

     //destroy
    public function destroy(AMC $amc, $id)
    {
        try{

            $amc = AMC::find($id);
             
            if(!$amc){
                throw new Exception("amc not found");
            }else{
                $amc->delete();
                $response = [
                    "message" => "amc deleted successfully",
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

    //showData
    public function showData()
    {
      try{    
            $amc = DB::table('amc');

            if(!$amc){
               throw new Exception("amc not found");
            }else{
                $amc = DB::table('assets')
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->select('vendorName','periodFrom','periodTo','servicePattern','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType','assets.assetName')
                    ->get();
                        
                $response=[
                    "message" => "Annual maintainence contract List",
                    "data" => $amc
                ];
                $status = 200; 
            } 
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


