<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Exports\AllocationExport;
use Maatwebsite\Excel\Facades\Excel;
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

            $allocation->user = $request->user; 

            if($allocation->userType == 'department'){
                $allocation->userDepartment = $request->userDepartment;
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

    //update
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
            
            $allocation->user = $request->user; 

            if($allocation->userType == 'department'){
                $allocation->userDepartment = $request->userDepartment;
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

    //Get the EmpID from Users table
    public function getEmpId()
    {
        try{

            $empId = Users::all();

            if(!$empId){
                throw new Exception("empId not found");
            }else{   

                $empId = DB::table('users')
                 ->select('id','employee_id')
                 ->get();
                
                $response = [
                    'data' => $empId,
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

    //Fetch the EmployeeName based on EmployeeId from Users table
    public function getEmpName($id)
    {
        try{
        
            $empName = DB::table('users')->where('employee_id','=',$id)->get();

            if(count($empName)<=0){
               throw new Exception("data not found");

            }else{
                $empName = DB::table('users')
                 ->where('employee_id','=',$id)
                 ->first('employee_name');

                $empName1 =$empName->employee_name;

                $response["empName"] = $empName1;
                $status = 200;
    
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

    //Fetch the UserName based on department from Users table
    public function getUser($id)
    {
        try{

            $empUser = Users::find($id);

            if(!$empUser){
                throw new Exception("data not found");
            }else{
    
            
                $empUser = DB::table('users')
                  ->where('department','=',$id)
                  ->select('id','employee_name')
                  ->get();

                $response = [
                    'data' =>  $empUser
                ]; 
                $status = 201;
            }
        
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

    //Showdata
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
                ->leftjoin('users as A','A.id','=','allocations.user')
                ->leftjoin('users as B','B.id','=','allocations.empId')
                ->select('allocations.*','departments.department_name as department',
                 'sections.section as section', 'assettypes.assetType as assetType',
                 'assets.assetName as assetName','assets.assetId as assetId',
                 'B.employee_id as empId',
                 'A.employee_name as user','departments.department_name as userDepartment',)
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
 
    //Downloading Allocation Data
    public function export(Request $request)
    {
        $fromDate =$request->fromDate;
        $toDate = $request->toDate;

        // $fromDate =$request->input(key:'fromDate');
        // $toDate = $request->input(key:'toDate');

        $query = DB::table('allocations')
            ->where('fromDate','>=',$fromDate) 
            ->where('toDate','<=', $toDate)
            ->join('departments','departments.id','=','allocations.department')
            ->join('assettypes','assettypes.id','=','allocations.assetType')
            ->join('assets','assets.id','=','allocations.assetName')
            ->join('sections','sections.id','=','allocations.section')
            ->join('users','users.id','=','allocations.user')
            ->select('allocations.id','departments.department_name as department',
             'sections.section as section', 'assettypes.assetType as assetType',
             'assets.assetName as assetName','assets.assetId as assetId',
             'users.employee_name as user');
  
       return Excel::download(new AllocationExport($query), 'Allocation.xlsx');
    }
}    
