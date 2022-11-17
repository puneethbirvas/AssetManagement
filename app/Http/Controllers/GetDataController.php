<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\department;
use App\Models\section;
use App\Models\assettype;
use App\Models\Vendor;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class GetDataController extends Controller
{
    //Retrving All Departments  
    public function getDepartment()
    {
        try{
            $department = department::all();

            if(!$department){
                throw new Exception("department not found");
            }else{   

                $department=DB::table('departments')->select('id','department_name')->get();
                
                $response = [
                    'success' => true,
                    'data' => $department,
                    'status' => 201
                ];
                $status = 201;   
            }

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
    

    //To Fetch The sections
    public function getSection( $id)
    { 
       try{ 
            $section=department::find($id);
        
            if(!$section){
                throw new Exception("data not found");
            }else{
                $section = DB::table('sections')->where('department','=',$id)->get();

                $response = [
                    'success' => true,
                    'data' => $section         
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404; 
        
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
            ];
            $status = 406; 
        }

        return response($response,$status);

    }
     
    //To Fetch The Sections
    public function getAssetType( $id)
    { 
       try{ 
            $assetType=section::find($id);
        
            if(!$assetType){
                throw new Exception("data not found");
            }else{
                $assetType = DB::table('assettypes')->where('section','=',$id)->get();

                $response = [
                    'success' => true,
                    'data' => $assetType         
                ];
                $status = 201;   
            }

        }catch(Exception $e){
                $response = [
                    "error" => $e->getMessage(),
                    "status" => 404
                ];
                $status = 404;       
        }catch(QueryException $e){
                $response = [
                    "error" => $e->errorInfo,
                ];
                $status = 406; 
        }

        return response($response,$status);
    }
    
    //To Fetch The AssetTypes
    public function getAssetName( $id)
    { 
       try{ 
            $assetName=assettype::find($id);

            if(!$assetName){
                throw new Exception("data not found");
            }else{
                $assetName = DB::table('assets')
                    ->where('assetType','=',$id)
                    ->get();

                $response = [
                    'success' => true,
                    'data' => $assetName         
                ];
                $status = 201;   
            }

        }catch(Exception $e){
                $response = [
                    "error" => $e->getMessage(),
                    "status" => 404
                ];
                $status = 404;       
        }catch(QueryException $e){
                $response = [
                    "error" => $e->errorInfo,
                ];
                $status = 406; 
        }

        return response($response,$status);
    }

    public function getVendor()
    {
        try{
            $Vendor = Vendor::all();

            if(!$Vendor){
                throw new Exception("VendorData not found");
            }else{   

                $Vendor=DB::table('vendors')->select('id as vendorId','vendorName')->get();

                $response = [
                    'success' => true,
                    'data' => $Vendor,
                    'status' => 201
                ];
                $status = 201;   
            }

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
    
    public function getVendorData($id)
    {
        try{
            $VendorData = Vendor::find($id);

            if(!$VendorData){
                throw new Exception("VendorData not found");
            }else{   
                
                $VendorData = DB::table('vendors')
                    ->where('id','=',$id)
                    ->select('contactNo','email','address')
                    ->get();

                $response = [
                    'success' => true,
                    'data' => $VendorData,
                    'status' => 201
                ];
                $status = 201;   
            }

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
}
