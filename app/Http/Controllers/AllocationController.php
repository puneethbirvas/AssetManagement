<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class AllocationController extends Controller
{
    public function store(Request $request)
    {
        try{
            $allocation = new Allocation;
            $allocation->department = $request->department;
            $allocation->section  = $request->section ;
            $allocation->assetType = $request->assetType;
            $allocation->assetName = $request->assetName;
            $allocation->userType = $request->userType;
            
            if($allocation->userType == 'empId'){
                $allocation->empId = $request->empId;
                
            }
            if($allocation->userType == 'department'){
                $allocation->userDepartment = $request->userDepartment;
                $allocation->user = $request->user; 
            }
            $allocation->position = $request->position;
            if($allocation->position == 'temporary'){
                $allocation->fromDate = $request->fromDate;
                $allocation->toDate = $request->toDate; 
            }
            if($allocation->position == 'permanent'){
                $allocation->fromDate = null;
                $allocation->toDate =  null;
            }

            $allocation->save();

            $response = [
                'success' => true,
                'message' => "successfully added",
                'status' => 201
            ];
            $status = 201;   
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
     
        return response($response, $status);        
    }

    public function update(Request $request, $id)
    {
        try{
            $allocation = Allocation::find($id);          
            if(!$allocation){
                throw new Exception("data not found");
            }
            $allocation->department = $request->department;
            $allocation->section  = $request->section ;
            $allocation->assetType = $request->assetType;
            $allocation->assetName = $request->assetName;
            $allocation->userType = $request->userType;
            
            if($allocation->userType == 'empId'){
                $allocation->empId = $request->empId;
               
            }
            if($allocation->userType == 'department'){
                $allocation->userDepartment = $request->userDepartment;
                $allocation->user = $request->user; 
            }
            $allocation->position = $request->position;
            if($allocation->position == 'temporary'){
                $allocation->fromDate = $request->fromDate;
                $allocation->toDate = $request->toDate; 
            }
            if($allocation->position == 'permanent'){
                $allocation->fromDate = null;
                $allocation->toDate =  null;
            }
           
            $allocation->save();

            $response = [
                'success' => true,
                'message' => "details updated successfully",
            ];
            $status = 201;   
            

        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }

    public function getEmpName($id)
    {
        $empName = DB::table('users')->where('employee_id','=',$id)
                ->select('employee_name')
                ->get();

        return ($empName);
       
    }

    public function showData(Request $request)
    {
      try{    
            $fromDate =$request->fromDate;
            $toDate = $request->toDate;

            $result = DB::table('allocations')
                    ->where('fromDate','>=',$fromDate) 
                    ->where('toDate','<=', $toDate)
                    ->join('departments','departments.id','=','allocations.department')
                    ->join('assettypes','assettypes.id','=','allocations.assetType')
                    ->join('assets','assets.id','=','allocations.assetName')
                    ->join('sections','sections.id','=','allocations.section')
                    ->select('departments.department_name as department','sections.section as 
                      section','assettypes.assetType as assetType','assets.assetName asassetName','assets.assetId as assetId','users.user_name as user')
                    ->join('users','users.id','=','allocations.user')
                    ->get();

            if(!$result){
              throw new Exception("data not found");
            }
            $response=[
             "message" => "Allocations List",
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
}    