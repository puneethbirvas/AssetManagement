<?php

namespace App\Http\Controllers;

use App\Models\RequestService;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        try{
            $service = new RequestService;

            $service->mId = $request->mId;
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

    public function showServiceRequest()
    {
        try{
            $data = DB::table('request_services')->select('request_services.*','department_name as department')
                    ->join('departments','departments.id','=','request_services.department')
                    ->get();
                
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

    public function showServiceRequest1($id)
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
            $data = DB::table('maintenances')->get();

            $response = [
                "data" => $data,
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
}
