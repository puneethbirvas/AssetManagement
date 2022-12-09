<?php

namespace App\Http\Controllers;

use App\Models\RequestService;
use Illuminate\Http\Request;
use App\Exports\RequestServiceExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class RequestServiceController extends Controller
{
    public function store(Request $request)
    {
        try{
            $service = new RequestService;

            $service->department = $request->department;
            $service->section = $request->section;
            $service->assetType  = $request->assetType ;
            $service->assetName = $request->assetName;
            $service->vendorName = $request->vendorName;
            $service->vendorEmail = $request->vendorEmail;
            $service->vendorAddress = $request->vendorAddress;
            $service->vendorAddress = $request->vendorAddress;
            $service->vendorPhone = $request->vendorPhone;
            $service->gstNo = $request->gstNo;
            $service->dateOrDay = $request->dateOrDay;

            if($service->dateOrDay == "date"){
                $service->expectedDate = $request->expectedDate;
                $service->expectedDay = null;
            }

            if($service->dateOrDay == "day"){
            $service->expectedDate = null;
            $service->expectedDay = $request->expectedDay;
            }
            $service->eWayBill  = $request->eWayBill ;
            $service->chargable = $request->chargable;
            $service->returnable = $request->returnable;
            $service->delivery = $request->delivery;
            $service->jobWork  = $request->jobWork ;
            $service->repair = $request->repair;
            $service->personName = $request->personName;

            $service->save();

            $response = [
                "data" => "details added successfully",
                "status" => 200
            ];
            $status = 200;

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


    public function showServiceRequest($id)
    {
        try{
            $data = DB::table('request_services')->where('id','=',$id)->select('*')->get();

            $response =[
                "data" => $data,
                "status" =>200
            ];
            $status = 200;

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

    public function showMaintenance()
    {
        try{
            $maintenance = DB::table('maintenances')
                ->select('maintenances.*','departments.department_name as department','sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName','users.user_name as userName')
                ->join('users','users.id','=','maintenances.userName')
                ->join('departments','departments.id','=','maintenances.department')
                ->join('sections','sections.id','=','maintenances.section')
                ->join('assettypes','assettypes.id','=','maintenances.assetType')
                ->join('assets','assets.id','=','maintenances.assetName')
                ->get(); 

            if(count($maintenance)<=0){
                throw new Exception("Data not found");
            }
        
                $response = [
                    "data" => $maintenance,
                    "status" => 200
                ];
                $status = 200;

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

    public function showMaintenance1($id)
    {
        try{
            $maintenance = DB::table('maintenances')
                ->select('maintenances.*','departments.department_name as department','sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName')
                ->where('maintenances.id','=',$id)
                ->join('departments','departments.id','=','maintenances.department')
                ->join('sections','sections.id','=','maintenances.section')
                ->join('assettypes','assettypes.id','=','maintenances.assetType')
                ->join('assets','assets.id','=','maintenances.assetName')
                ->get(); 

            if(count($maintenance)<=0){
                throw new Exception("Data not found");
            }
        
                $response = [
                    "data" => $maintenance,
                    "status" => 200
                ];
                $status = 200;

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


    public function updateServiceStatus(Request $request, $id)
    {
        try{

            $data = DB::table('request_services')->where('assetName','=',$id)->first();
            $get = $data->id;

            $update = RequestService::find($get);
            $update->serviceStatus = $request->serviceStatus;
            $update->save();

            $response = [
                "data" => "status updated successfully!",
                "status" => 200
            ];
            $status = 200;

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

    public function export()
    {
      $query = DB::table('maintenances')
            ->select('maintenances.id','departments.department_name as department','sections.section as section','assets.assetName as assetName','amcStatus','warrantyStatus','insuranceStatus','problemNote','users.user_name as userName')
            ->join('users','users.id','=','maintenances.userName')
            ->join('departments','departments.id','=','maintenances.department')
            ->join('sections','sections.id','=','maintenances.section')
            ->join('assets','assets.id','=','maintenances.assetName')
            ->get(); 
    
        return Excel::download(new RequestServiceExport($query), 'RequestServices.xlsx');
    }


}
