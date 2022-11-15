<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;
use DB;
use Str;
use Storage;

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
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $image = str_replace($replace, '', $image); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(10).'.'.$extension;
            $imagePath = '/storage'.'/'.$imageName;
            Storage::disk('public')->put($imageName, base64_decode($image));
            $maintenance->bpImages1 = $imagePath;

            $maintenance->bpImages2 = $request->bpImages2;
            $maintenance->bpImages3 = $request->bpImages3;
            $maintenance->bpImages4 = $request->bpImages4;
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

    public function getMaintenanceId(){

        $last = DB::table('maintenances')->latest( 'id')->first();
    
        if(!$last){
           $user =  "1";
        }else{
            $user = $last->id + 1;
        }
        $get = "ms-".$user;
    
        return $get;
    }
}
