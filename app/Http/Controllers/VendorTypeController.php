<?php

namespace App\Http\Controllers;

use App\Models\VendorType;
use Illuminate\Http\Request;

class VendorTypeController extends Controller
{

    // to store vendorTypes
    public function store(Request $request)
    {
       try{                
            $vendorType = new VendorType;
                
            $vendorType->vendorType = $request->vendorType;
            $vendorType->description = $request->description;
            
            $vendorType->save();
            $response = [
                'success' => true,
                'message' => $request->vendorType." added successfully",
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

     // Displaying VendorsTypes
     public function showData(VendorType $vendorType)
     {
 
         try{
             $vendorType = VendorType::all();
 
             if(!$vendorType){
                 throw new Exception("VendorTypes not found");
             }else{
                 $response = [
                     'success' => true,
                     'data' => $vendorType         
                 ];
                 $status = 201;   
             return response($response,$status);
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

}
