<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\BaseController as BaseController;
use Exception;
use Illuminate\Database\QueryException;
use Validator;
use DB;

class VendorController extends Controller
{
 
    // vendorRegistration
    public function store(Request $request)
    {
       try{                
            $vendor = new Vendor;
                
            $vendor->vendorName = $request->vendorName;
            $vendor->vendorType = $request->vendorType;
            $vendor->address = $request->address;
            $vendor->email = $request->email;
            $vendor->altEmail = $request->altEmail;
            $vendor->contactNo = $request->contactNo;
            $vendor->altContactNo = $request->altContactNo;
            $vendor->contactPerson = $request->contactPerson;
            $vendor->reMarks = $request->reMarks;
            $vendor->gstNo = $request->gstNo;
            
            //imageStoring
            $image = $request->gstCertifiacate;  // your base64 encoded
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $image = str_replace($replace, '', $image); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(10).'.'.$extension;
            $imagePath = '/storage'.'/'.$imageName;
            Storage::disk('public')->put($imageName, base64_decode($image));

            $vendor->gstCertifiacate = $imagePath;

            $image = $request->msmeCertifiacate;  // your base64 encoded
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $image = str_replace($replace, '', $image); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(10).'.'.$extension;
            $imagePath = '/storage'.'/'.$imageName;
            Storage::disk('public')->put($imageName, base64_decode($image));

            $vendor->msmeCertifiacate = $imagePath;

            $image = $request->canceledCheque;  // your base64 encoded
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $image = str_replace($replace, '', $image); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(10).'.'.$extension;
            $imagePath = '/storage'.'/'.$imageName;
            Storage::disk('public')->put($imageName, base64_decode($image));

            $vendor->canceledCheque = $imagePath;

            $vendor->save();
            $response = [
                'success' => true,
                'message' => $request->vendorName." Registered successfully",
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
    public function update(Request $request, $id)
    {
    
        try{

            $vendor = Vendor::find($id);          
            if(!$vendor){
                throw new Exception("Vendor name not found");
            }
                
            $vendor->vendorName = $request->vendorName;
            $vendor->vendorType = $request->vendorType;
            $vendor->address = $request->address;
            $vendor->email = $request->email;
            $vendor->altEmail = $request->altEmail;
            $vendor->contactNo = $request->contactNo;
            $vendor->altContactNo = $request->altContactNo;
            $vendor->contactPerson = $request->contactPerson;
            $vendor->reMarks = $request->reMarks;
            $vendor->gstNo = $request->gstNo;

            if($file = $request->hasFile('gstCertificate')) {
                $file = $request->file('gstCertificate') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ;
                $file->move($destinationPath,$fileName);
                $vendor->gstCertificate = '/images/'.$fileName ;
            }
            if($file = $request->hasFile('msmeCertificate')) {
                $file = $request->file('msmeCertificate') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ; 
                $file->move($destinationPath,$fileName);
                $vendor->msmeCertificate = '/images/'.$fileName ;
            }
            if($file = $request->hasFile('canceledCheque')) {
                $file = $request->file('canceledCheque') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images/' ;
                $file->move($destinationPath,$fileName);
                $vendor->canceledCheque = '/images/'.$fileName ;
            }

            $vendor->save();
            $response = [
                'success' => true,
                'message' => "Vendor updated successfully",
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
    public function destroy(Vendor $vendor, $id)
    {
        try{
            $vendor = Vendor::find($id);
            if(!$vendor){
                throw new Exception("Vendor name not found");
            }else{
                $vendor->delete();
                $response = [
                    "message" => "Vendor deleted successfully",
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

    // Displaying VendorsDetails
    public function showData(Vendor $vendor)
    {

        try{
            $vendor = DB::table('vendors')->select('id','vendorName','address','email','contactNo','contactPerson')->orderby('id','asc')->get();

            if(!$vendor){
                throw new Exception("Vendor details not found");
            }else{
                $response = [
                    'success' => true,
                    'data' => $vendor         
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