<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Storage;
use Str;
use DB;

class CertificateController extends Controller
{
    public function store(Request $request)
    {
        try{
            $certificate = new Certificate;

            $certificate->vendorName = $request->vendorName;
            $certificate->certificateDate = $request->certificateDate;
            $certificate->expireDate = $request->expireDate;
            $certificate->premiumCost = $request->premiumCost;

            // Certificate document
            $image = $request->certificateDoc;
            if($image){ // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $certificate->certificateDoc = $imagePath;
            }
           
            $certificate->inspectionPattern = $request->inspectionPattern;
            $inspection = $request->inspectionPattern;

            if($inspection == 'inspection1')
            {
                $certificate->inspection1 = $this->inspection1($request);
            }

            if($inspection == 'inspection2')
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
            }

            if($inspection == 'inspection3')
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
            }

            if($inspection == 'inspection4')
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
                $certificate->inspection4 = $this->inspection4($request);
            }

            if($inspection == 'inspection5')
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
                $certificate->inspection4 = $this->inspection4($request);
                $certificate->inspection5 = $this->inspection5($request);
            }

            $certificate->department = $request->department;
            $certificate->section = $request->section;
            $certificate->assetType = $request->assetType;
            $certificate->assetName = $request->assetName;
        
            $certificate->save();
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
            $certificate = Certificate::find($id);          
            if(!$certificate){
                throw new Exception("Data not found");
            }

            $certificate->vendorName = $request->vendorName;
            $certificate->certificateDate = $request->certificateDate;
            $certificate->expireDate = $request->expireDate;
            $certificate->premiumCost = $request->premiumCost;

            // Certificate document
            $image = $request->certificateDoc;
            if($image){ // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $certificate->certificateDoc = $imagePath;
            }
            
            $certificate->department = $request->department;
            $certificate->section = $request->section;
            $certificate->assetType = $request->assetType;
            $certificate->assetName = $request->assetName;
        
            $certificate->save();
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
    public function destroy($id)
    {
        try{

            $certificate = Certificate::find($id);
             
            if(!$certificate){
                throw new Exception("Data not found");
            }else{
                $certificate->delete();
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
            $certificate = DB::table('certificates');

            if(!$certificate){
               throw new Exception("Data not found");
            }else{
                $certificate = DB::table('certificates')
                    ->join('vendors','vendors.id','=','certificates.vendorName')
                    ->join('departments','departments.id','=','certificates.department')
                    ->join('sections','sections.id','=','certificates.section')
                    ->join('assettypes','assettypes.id','=','certificates.assetType')
                    ->join('assets','assets.id','=','certificates.assetName')
                    ->select('vendors.vendorName as vendorName','certificateDate','expireDate','inspectionPattern','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType',
                      'assets.assetName as assetName')
                    ->get();
                        
                $response=[
                    "message" => "Annual maintainence contract List",
                    "data" => $certificate
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

    public function inspection1(Request $request)
    {
        $c1DateFrom = $request->c1DateFrom;
        $c1DateTo = $request->c1DateTo;
        $inspection1 = $c1DateFrom."+".$c1DateTo;

        return $inspection1;

    }
    public function inspection2(Request $request)
    {
        $c2DateFrom = $request->c2DateFrom;
        $c2DateTo = $request->c2DateTo;
        $inspection2 = $c2DateFrom."+".$c2DateTo;

        return $inspection2;
    }

    public function inspection3(Request $request)
    {
        $c3DateFrom = $request->c3DateFrom;
        $c3DateTo = $request->c3DateTo;
        $inspection3 = $c3DateFrom."+".$c3DateTo;

        return $inspection3;
    }

    public function inspection4(Request $request)
    {
        $c4DateFrom = $request->c4DateFrom;
        $c4DateTo = $request->c4DateTo;
        $inspection4 = $c4DateFrom."+".$c4DateTo;

        return $inspection4;
    }

    public function inspection5(Request $request)
    {
        $c5DateFrom = $request->c5DateFrom;
        $c5DateTo = $request->c5DateTo;
        $inspection5 = $c5DateFrom."+".$c5DateTo;

        return $inspection5;
    }
}
