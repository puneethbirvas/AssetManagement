<?php

namespace App\Http\Controllers;
use App\Models\Maintenance;
use DB;
use Str;
use Storage;
use Exception;
use Illuminate\Database\QueryException;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
   public function store(Request $request)
   {
        try{
      
            $maintenance = new Maintenance;

            $maintenance->maintenanceId = $request->maintenanceId;
            $maintenance->assetName = $request->assetName;
            $maintenance->maintenanceType = $request->maintenanceType;
            $maintenance->severity = $request->severity;
            $maintenance->problemNote = $request->problemNote;
            
            $image = $request->bpImages1;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $maintenance->bpImages1 = $imagePath;
            }

            $image = $request->bpImages2;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $maintenance->bpImages2 = $imagePath;
            }

            $image = $request->bpImages3;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $maintenance->bpImages3 = $imagePath;
            }

            $image = $request->bpImages4;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $maintenance->bpImages4 = $imagePath;
            }

            $maintenance->partsOrConsumable = $request->partsOrConsumable;
            $maintenance->affectedMachine = $request->affectedMachine;
            $maintenance->affectedManHours = $request->affectedManHours;

            $type1 = $request->type1;

            if($type1 == "shutDown"){
                $maintenance->shutdownOrUtilization = "shutDown";
            }

            if($type1 == "machineUtilization"){
                $maintenance->shutdownOrUtilization = $request->shutdownOrUtilization;
                $maintenance->machineDetails = $request->machineDetails;
            }

            $type2 = $request->type2;

            if($type2 == "off"){
                $maintenance->offOrUtilization = "off";
            }

            if($type2 == "manHoursUtilization"){
                $maintenance->offOrUtilization = $request->offOrUtilization;
                $maintenance->manHoursDetails = $request->manHoursDetails;
            }

            $maintenance->dateFrom = $request->dateFrom;
            $maintenance->dateTo  = $request->dateTo ;
            $maintenance->timeFrom = $request->timeFrom;
            $maintenance->timeTo = $request->timeTo;
            $maintenance->action = "pending";

            $maintenance->save();
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

    //shoow Maintenance data
    public function showData()
    {
      try{    
            $manintenance = DB::table('maintenances');

            if(!$manintenance){
               throw new Exception("manintenance not found");
            }else{
                $manintenance = DB::table('maintenances')
                    ->join('assets','assets.id','=','maintenances.assetName')
                    ->get();
                        
                $response=[
                    "message" => "manintenance List",
                    "data" => $manintenance
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


   public function getMaintenanceId()
   {
        $last = DB::table('maintenances')->latest( 'id')->first();

            if(!$last){
            $user =  "1";
            }else{
                $user = $last->id + 1;
            }
            $get = "ms-".$user;

            $response = [
                'success' => true,
                'data' =>  $get,
                'status' => 201
            ];
            $status = 201;  

        return Response($response, $status);    
    }

   public function updateAction(Request $request,$id)
   {
        try{

            $maintenance = Maintenance::find($id); 
            
            $maintenance->action = $request->action;

            $maintenance->save();
            $response = [
                'success' => true,
                'message' => "details updated successfully",
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

       return Response($response, $status);
    }

   public function updateClosedMaintenance(Request $request,$id)
   {
        try{

            $maintenance = Maintenance::find($id); 
            
            $maintenance->closedMaintenance = $request->closedMaintenance;

            $maintenance->save();
            $response = [
                'success' => true,
                'message' => "details updated successfully",
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

        return Response($response, $status);
    }

   public function aprovedShowData()
   {
        try{
            
            $maintenance = DB::table('maintenances')
              ->where('action','=','aproved')
              ->join('assets','assets.id','=','maintenances.assetName')
              ->get();


                if(!$maintenance){
                    throw new Exception("data not found");
                } 

            $response = [
                'success' => true,
                'data' => $maintenance,
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

      return Response($response, $status);
    }


    public function pendingShowData()
    {
        try{

            $maintenance = DB::table('maintenances')->where('action','=','pending')->get();
             
            
            if(!$maintenance){
                throw new Exception("data not found");
            } 

            $response = [
                'success' => true,
                'data' => $maintenance,
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

       return Response($response, $status);
    }
 

    public function rejectedShowData()
    {
        try{

            $maintenance = DB::table('maintenances')->where('action','=','rejected')->get();
            
            if(!$maintenance){
                throw new Exception("data not found");
            } 

            $response = [
                'success' => true,
                'data' => $maintenance,
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
         
        return Response($response, $status);
    }

    public function showClosedMaintenance()
    {
        try{
            $maintenance = DB::table('maintenances')->where('closedMaintenance','!=','null')->get();
        
                if(!$maintenance){
                    throw new Exception("data not found");
                } 

            $response = [
                'success' => true,
                'data' => $maintenance,
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
     
      return Response($response, $status);

    }


    public function showStatus($id)
    {
        try{
         
            $amc = DB::table('amcs')->where('assetName','=',$id)->get();

                if(count($amc)>0){
                    $data["amc"] = "Amc available";

                }else{
                    $data["amc"] = "Amc not available";
                }

            $warranty = DB::table('assets')->where('id','=',$id)->where('warrantyStartDate','!=',null)->first();
                if($warranty){
                    $data["warranty"] = "warranty available";
                    $data["startDate"] = $warranty->warrantyStartDate;
                    $data["endDate"] = $warranty->warrantyEndDate;

                }else{
                    $data["warranty"] = "warranty not available";
                }

            $data["warrantyType"] = "NA";

            $response = $data;
            $status = 406; 

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

      return Response($response,$status);
    }
}   