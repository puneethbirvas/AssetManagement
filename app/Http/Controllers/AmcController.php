<?php

namespace App\Http\Controllers;

use App\Models\Amc;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Storage;
use Str;
use DB;

class AMCController extends Controller
{

    public function store(Request $request)
    {
        try{
            $amc = new Amc;

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
                $amc->amcDoc = $imagePath;
            }
           
            $amc->servicePattern = $request->servicePattern;
            $service = $request->servicePattern;

            if($service == 'service1')
            {
                $amc->service1 = $this->service1($request);
            }

            if($service == 'service2')
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
            }

            if($service == 'service3')
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
                $amc->service3 = $this->service3($request);
            }

            if($service == 'service4')
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
                $amc->service3 = $this->service3($request);
                $amc->service4 = $this->service4($request);
            }

            if($service == 'service5')
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
                $amc->service3 = $this->service3($request);
                $amc->service4 = $this->service4($request);
                $amc->service5 = $this->service5($request);
            }

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
    public function update(Request $request,$id)
    {
        try{
            $amc = Amc::find($id);          
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
                $amc->amcDoc = $imagePath;

            }
            
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
    public function destroy(Amc $amc, $id)
    {
        try{

            $amc = Amc::find($id);
             
            if(!$amc){
                throw new Exception("amc not found");
            }else{
                $amc->delete();
                $response = [
                    "message" => "data deleted successfully",
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
                $amc = DB::table('amcs')
                    ->join('vendors','vendors.id','=','amcs.vendorName')
                    ->join('departments','departments.id','=','amcs.department')
                    ->join('sections','sections.id','=','amcs.section')
                    ->join('assettypes','assettypes.id','=','amcs.assetType')
                    ->join('assets','assets.id','=','amcs.assetName')
                    ->select('vendors.vendorName as vendorName','periodFrom','periodTo','servicePattern','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName')
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

    public function service1(Request $request)
    {
        $s1DateFrom = $request->s1DateFrom;
        $s1DateTo = $request->s1DateTo;
        $s1runHours = $request->s1runHours;
        $service1 = $s1DateFrom."+".$s1DateTo."+".$s1runHours;

        return $service1;

    }
    public function service2(Request $request)
    {
        $s2DateFrom = $request->s2DateFrom;
        $s2DateTo = $request->s2DateTo;
        $s2runHours = $request->s2runHours;
        $service2 = $s2DateFrom."+".$s2DateTo."+".$s2runHours;

        return $service2;
    }

    public function service3(Request $request)
    {
        $s3DateFrom = $request->s3DateFrom;
        $s3DateTo = $request->s3DateTo;
        $s3runHours = $request->s3runHours;
        $service3 = $s3DateFrom."+".$s3DateTo."+".$s3runHours;

        return $service3;
    }

    public function service4(Request $request)
    {
        $s4DateFrom = $request->s4DateFrom;
        $s4DateTo = $request->s4DateTo;
        $s4runHours = $request->s4runHours;
        $service4 = $s4DateFrom."+".$s4DateTo."+".$s4runHours;
        return $service4;
    }

    public function service5(Request $request)
    {
        $s5DateFrom = $request->s5DateFrom;
        $s5DateTo = $request->s5DateTo;
        $s5runHours = $request->s5runHours;
        $service5 = $s5DateFrom."+".$s5DateTo."+".$s5runHours;

        return $service5;
    }
}


