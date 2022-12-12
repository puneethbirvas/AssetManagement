<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Exports\InsuranceExport;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use Str;
use DB;
use Exception;
use Illuminate\Database\QueryException;


class InsuranceController extends Controller
{
    public function store(Request $request)
    {
        try{

            $insurance = new Insurance;
            
            $insurance->vendorName = $request->vendorName;
            $insurance->periodFrom = $request->periodFrom;
            $insurance->periodTo = $request->periodTo;
            $insurance->premiumCost = $request->premiumCost;

            //Insurance  document
            $image = $request->insuranceDoc;
            if($image){ // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $insurance->insuranceDoc = $imagePath;
            }
            
            $insurance->department = $request->department;
            $insurance->section = $request->section;
            $insurance->assetType = $request->assetType;
            $insurance->assetName = $request->assetName;
            
            $insurance->save();
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

            $insurance = Insurance::find($id);          
            if(!$insurance){
                throw new Exception("Data not found");
            }
    
            $insurance->vendorName = $request->vendorName;
            $insurance->periodFrom = $request->periodFrom;
            $insurance->periodTo = $request->periodTo;
            $insurance->premiumCost = $request->premiumCost;
    
            // Amc document
            $image = $request->insuranceDoc;
            if($image){ // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $insurance->insuranceDoc = $imagePath;
    
            }
                
            $insurance->department = $request->department;
            $insurance->section = $request->section;
            $insurance->assetType = $request->assetType;
            $insurance->assetName = $request->assetName;
            
            $insurance->save();
            $response = [
                'success' => true,
                'message' => "successfully Updated",
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

            $insurance = Insurance::find($id);
                
            if(!$insurance){
                throw new Exception("amc not found");
            }else{
                $insurance->delete();
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

            $insurance = DB::table('insurances')
                ->join('vendors','vendors.id','=','insurances.vendorName')
                ->join('departments','departments.id','=','insurances.department')
                ->join('sections','sections.id','=','insurances.section')
                ->join('assettypes','assettypes.id','=','insurances.assetType')
                ->join('assets','assets.id','=','insurances.assetName')
                ->select('insurances.id','vendors.vendorName as vendorName','periodFrom','periodTo',
                  'departments.department_name as department', 'sections.section as section',
                  'assettypes.assetType as assetType','assets.assetName as assetName')
                ->get();
                
                if(!$insurance){
                    throw new Exception("amc not found");
                }
                          
            $response=[
              "message" => "insurance List",
              "data" => $insurance
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

    //To display the Insurance Due(End) Date
    public function insuranceDue(Request $request,$id)
    {
        try{    

            $periodFrom =$request->periodFrom;
            $periodTo = $request->periodTo;

            $result = DB::table('insurances')
                    ->where('insurances.assetType','=',$id)
                    ->where('periodFrom','>=',$periodFrom) 
                    ->where('periodTo','<=', $periodTo)
                    ->join('vendors','vendors.id','=','insurances.vendorName')
                    ->join('assets','assets.id','=','insurances.assetName')
                    ->select('insurances.id','vendors.vendorName as vendorName','periodTo as insuranceDate',
                     'assets.assetName as assetName')
                    ->get();

                if(!$result){
                 throw new Exception("data not found");
                }

            $response=[
             "message" => "Insurance Due Date List",
             "data" => $result
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

    //To Display The Insurance data with in end of 7 days
    public function viewInsuranceRenewal()
    {
        try{ 

            $result=DB::table('insurances')
                ->whereBetween('periodTo', [now(), now()->addDays(7)])
                ->join('departments','departments.id','=','insurances.department')
                ->join('assets','assets.id','=','insurances.assetName')
                ->select( 'insurances.id','departments.department_name as department',
                 'assets.assetName as  assetName','periodFrom as insuranceStartDate','periodTo as insuranceEndDate')
                ->get();
                
                if(count($result)<=0){
                    throw new Exception("data not found");
                } 
                
            $response = [
                'success' => true,
                'data' => $result,
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

    //Update The Insurance (Date) Renewal
    public function renewalInsurance(Request $request,$id)
    {
        try{    
             
            $insurance = Insurance::find($id); 

            if(!$insurance){
                throw new Exception("Data not found");
            }
                
            $insurance->periodFrom = $request->periodFrom;
            $insurance->periodTo = $request->periodTo;
            $insurance->save();

            $response=[
                "message" => "Updated Sucessfully",
                "status" => 200
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

    //Downloading insurance Data
    public function export()
    {
      $query = DB::table('insurances')
        ->join('vendors','vendors.id','=','insurances.vendorName')
        ->join('departments','departments.id','=','insurances.department')
        ->join('sections','sections.id','=','insurances.section')
        ->join('assettypes','assettypes.id','=','insurances.assetType')
        ->join('assets','assets.id','=','insurances.assetName')
        ->select('insurances.id','vendors.vendorName as vendorName','periodFrom','periodTo',
            'departments.department_name as department', 'sections.section as section',
            'assettypes.assetType as assetType','assets.assetName as assetName')
        ->get();
  
      return Excel::download(new InsuranceExport($query), 'Insurance.xlsx');
    }

}     