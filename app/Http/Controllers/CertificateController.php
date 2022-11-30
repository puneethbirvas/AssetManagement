<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Exports\CertificateExport;
use Maatwebsite\Excel\Facades\Excel;
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

            if($inspection == 1)
            {
                $certificate->inspection1 = $this->inspection1($request);
            }

            if($inspection == 2)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
            }

            if($inspection == 3)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
            }

            if($inspection == 4)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
                $certificate->inspection4 = $this->inspection4($request);
            }

            if($inspection == 5)
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

    // to merge 3 inputs into one
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
                    ->select('certificates.id','vendors.vendorName as vendorName','certificateDate','expireDate','inspectionPattern','departments.department_name as department',
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

    //view Certificate Data by ID
    public function showData1($id)
    {
      try{    
            $data = Certificate::find($id);

            if(!$data){
               throw new Exception("Data not found");

            }else{
                 
                $data = DB::table('certificates')
                    ->where('certificates.id','=',$id)
                    ->join('vendors','vendors.id','=','certificates.vendorName')
                    ->get();
                
                if(count($data)>0){
                    $vendorName = $data[0]->vendorName;
                    $inspectionPattern = $data[0]->inspectionPattern;
            
                    $rawData = array();
                    $rawData["id"] = $id;
                    $rawData["vendorName"] = $vendorName;
                    $rawData["inspectionPattern"] = $inspectionPattern;
            
                    $data1 = array();

                    for($i=1;$i<=number_format($inspectionPattern);$i++){
                                
                        if($i == 1){
                            $inspectionSplit1 = explode("+",$data[0]->inspection1);
            
                            $rawData["c1startDate"] = $inspectionSplit1[0];
                            $rawData["c1endDate"] = $inspectionSplit1[1];
                        }
            
                        if($i == 2){
                            $inspectionSplit2 = explode("+",$data[0]->inspection2);
                            $rawData["c2startDate"] = $inspectionSplit2[0];
                            $rawData["c2endDate"] = $inspectionSplit2[1];
                        }
            
                        if($i == 3){
                            $inspectionSplit3 = explode("+",$data[0]->inspection3);
                            $rawData["c3startDate"] = $inspectionSplit3[0];
                            $rawData["c3endDate"] = $inspectionSplit3[1];
                        }
            
                        if($i == 4){
                            $inspectionSplit4 = explode("+",$data[0]->inspection4);
                            $rawData["c4startDate"] = $inspectionSplit4[0];
                            $rawData["c4endDate"] = $inspectionSplit4[1];
                        }
            
                        if($i == 5){
                            $inspectionSplit5 = explode("+",$data[0]->inspection5);
                            $rawData["c5startDate"] = $inspectionSplit5[0];
                            $rawData["c5endDate"] = $inspectionSplit5[1];
                        }
                    }

                    $data[] = $rawData;
                    $response["data"][] = $rawData;
                    $status = 200; 
                }    
                
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


   
    // to explode the data
    public function getDate1($id)
    {
        $last =DB::table('certificates')->where('assetName','=',$id)->select('inspection1')->first();
        $last = $last->inspection1;
        $get = explode('+',$last);
        $get1 = $get[0];
        $get2 = $get[1];
  
        $response = [
            "c1DateFrom" =>$get1,
            "c1To" =>$get2,
        ]; 

        return $response;
    }   
      
    public function getDate2($id)
    {
        $last =DB::table('certificates')->where('assetName','=',$id)->select('inspection2')->first();
        $last = $last->inspection2;
        $get = explode('+',$last);
        $get1 = $get[0];
        $get2 = $get[1];
  
        $response = [
            "c2DateFrom" =>$get1,
            "c2To" =>$get2,
        ]; 

        return $response;
    }
  
    public function getDate3($id)
    {
        $last =DB::table('certificates')->where('assetName','=',$id)->select('inspection3')->first();
        $last = $last->inspection3;
        $get = explode('+',$last);
        $get1 = $get[0];
        $get2 = $get[1];
  
        $response = [
            "c3DateFrom" =>$get1,
            "c3To" =>$get2,
        ]; 

        return $response;
    }
  
    public function getDate4($id)
    {
        $last =DB::table('certificates')->where('assetName','=',$id)->select('inspection4')->first();
        $last = $last->inspection4;
        $get = explode('+',$last);
        $get1 = $get[0];
        $get2 = $get[1];
  
        $response = [
            "c4DateFrom" =>$get1,
            "c4To" =>$get2,
        ]; 

        return $response;
    }
  
    public function getDate5( $id)
    {
        $last =DB::table('certificates')->where('assetName','=',$id)->select('inspection5')->first();
        $last = $last->inspection5;
        $get = explode('+',$last);
        $get1 = $get[0];
        $get2 = $get[1];
  
        $response = [
            "c5DateFrom" =>$get1,
            "c5To" =>$get2,
        ]; 
        
        return $response;
    }

    //To Get Certificate (VendorName,AssetName)
    public function showDetails($id)
    {
        $last = DB::table('certificates')
            ->where('certificates.assetName','=',$id)
            ->join('vendors','vendors.id','=','certificates.vendorName')
            ->select('certificates.id','vendors.vendorName as vendorName')
            ->first();
        return $last;

    }
  
    // to display service date
    public function showInspection($id)
    {
        try{
          
            $data  = DB::table('certificates')
                ->select('*','vendors.vendorName as vendorName')
                ->join('vendors','vendors.id','=','certificates.vendorName')
                ->where('assetName','=',$id)
                ->get();

            if(count($data)>0){
                $id = $data[0]->id;
                $vendorName = $data[0]->vendorName;
                $inspectionPattern = $data[0]->inspectionPattern;
    
                $rawData = array();
                $rawData["id"] = $id;
                $rawData["vendorName"] = $vendorName;
                $rawData["inspectionPattern"] = $inspectionPattern;
    
                $data1 = array();
                for($i=1;$i<=number_format($inspectionPattern);$i++){
                        
                    if($i == 1){
                        $inspectionSplit1 = explode("+",$data[0]->inspection1);
    
                        $rawData["c1startDate"] = $inspectionSplit1[0];
                        $rawData["c1endDate"] = $inspectionSplit1[1];
                    }
    
                    if($i == 2){
                        $inspectionSplit2 = explode("+",$data[0]->inspection2);
                        $rawData["c2startDate"] = $inspectionSplit2[0];
                        $rawData["c2endDate"] = $inspectionSplit2[1];
                    }
    
                    if($i == 3){
                        $inspectionSplit3 = explode("+",$data[0]->inspection3);
                        $rawData["c3startDate"] = $inspectionSplit3[0];
                        $rawData["c3endDate"] = $inspectionSplit3[1];
                    }
    
                    if($i == 4){
                        $inspectionSplit4 = explode("+",$data[0]->inspection4);
                        $rawData["c4startDate"] = $inspectionSplit4[0];
                        $rawData["c4endDate"] = $inspectionSplit4[1];
                    }
    
                    if($i == 5){
                        $inspectionSplit5 = explode("+",$data[0]->inspection5);
                        $rawData["c5startDate"] = $inspectionSplit5[0];
                        $rawData["c5endDate"] = $inspectionSplit5[1];
                    }
                }
                $data[] = $rawData;
                $response["data"][] = $rawData;
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
    
    //To display the Inspection Due(End) Date
    public function inspectionDue(Request $request,$id)
    {
        try{    

            $certificateDate =$request->certificateDate;
            $expireDate = $request->expireDate;

            $result = DB::table('certificates')
                ->where('certificates.assetType','=',$id)
                ->where('certificateDate','>=',$certificateDate) 
                ->where('expireDate','<=', $expireDate)
                ->join('vendors','vendors.id','=','certificates.vendorName')
                ->join('assets','assets.id','=','certificates.assetName')
                ->select('certificates.id','vendors.vendorName as vendorName',
                 'expireDate as inspectionDate','assets.assetName as assetName',)
                ->get();

                if(!$result){
                 throw new Exception("data not found");
                }

            $response=[
             "message" => "inspection Due Date List",
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
    
    //To Display The Certificate data with in end of 7 days
    public function viewCertificateRenewal()
    {
        try{

            $result=DB::table('certificates')
                    ->whereBetween('expireDate', [now(), now()->addDays(7)])
                    ->join('departments','departments.id','=','certificates.department')
                    ->join('assets','assets.id','=','certificates.assetName')
                    ->select( 'certificates.id','departments.department_name as department', 
                     'assets.assetName as assetName','certificateDate as certificateStartDate',
                     'expireDate as certificateEndDate')
                    ->get();
                
                if(!$result){
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
    
    //Update The Certificate (Date & Inspection) Renewal
    public function renewalCertificate(Request $request,$id)
    {
        try{    
             
            $certificate = Certificate::find($id); 

            if(!$certificate){
                throw new Exception("Data not found");
            }
                
            $certificate->certificateDate = $request->certificateDate;
            $certificate->expireDate = $request->expireDate;

            $certificate->inspectionPattern = $request->inspectionPattern;
            $inspection = $request->inspectionPattern;

            if($inspection == 1)
            {
                $certificate->inspection1 = $this->inspection1($request);
            }

            if($inspection == 2)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
            }

            if($inspection == 3)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
            }

            if($inspection == 4)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
                $certificate->inspection4 = $this->inspection4($request);
            }

            if($inspection == 5)
            {
                $certificate->inspection1 = $this->inspection1($request);
                $certificate->inspection2 = $this->inspection2($request);
                $certificate->inspection3 = $this->inspection3($request);
                $certificate->inspection4 = $this->inspection4($request);
                $certificate->inspection5 = $this->inspection5($request);
            }
            $certificate->save();

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
    
    //Downloading Certificate Data
    public function export()
    {
      $query = DB::table('certificates')
        ->join('vendors','vendors.id','=','certificates.vendorName')
        ->join('departments','departments.id','=','certificates.department')
        ->join('sections','sections.id','=','certificates.section')
        ->join('assettypes','assettypes.id','=','certificates.assetType')
        ->join('assets','assets.id','=','certificates.assetName')
        ->select('certificates.id','vendors.vendorName as vendorName','certificateDate','expireDate','inspectionPattern','departments.department_name as department',
            'sections.section as section','assettypes.assetType as assetType',
            'assets.assetName as assetName')
        ->get();
  
      return Excel::download(new CertificateExport($query), 'Certificate.csv');
    }
  
}
