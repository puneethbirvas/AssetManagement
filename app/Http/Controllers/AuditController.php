<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class AuditController extends Controller
{
    public function store(Request $request)
    {
        try{  

            $audit = new Audit;

            $audit->auditDate  = $request->auditDate ;
            $audit->department = $request->department;
            $audit->section =  $request->section;
            $audit->assetType = $request->assetType;
            $audit->auditName = $request->auditName;

            $audit->save();
            $response = [
                'success' => true,
                'message' => "successfully added",
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

    //Audit update
    public function update(Request $request, $id)
    {
        try{

            $audit = Audit::find($id);          
            if(!$audit){
                throw new Exception("data not found");
            }
            $audit->auditDate  = $request->auditDate ;
            $audit->department = $request->department ;
            $audit->section =  $request->section ;
            $audit->assetType = $request->assetType;
            $audit->auditName = $request->auditName;
           
            $audit->save();

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

    
    //destroy
    public function destroy(Audit $audit, $id)
    {
        try{
            $audit = Audit::find($id);

            if(!$audit){
                throw new Exception("data not found");
            }else{
                $audit->delete();
                $response = [
                    "message" => " deleted successfully",
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
        }catch(QueryException $e){
                $response = [
                    "error" => $e->errorInfo,
                ];
                $status = 406; 
        }

        return response($response,$status);
      
    }  

    // Displaying auditTypes
    public function showData(Audit $audit)
     {
        try{
            $audit = DB::table('audits')
                ->join('departments','departments.id','=','audits.department')
                ->join('sections','sections.id','=','audits.section')
                ->join('assettypes','assettypes.id','=','audits.assetType')
                ->select('audits.*','departments.department_name as department',
                  'sections.section as section','assettypes.assetType as assetType' )
                ->get();

            if(!$audit){
                throw new Exception("data not found");
            }else{
                $response = [
                    'success' => true,
                    'data' => $audit         
                ];
                $status = 201;   
            }
 
        }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404;       
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
            ];
            $status = 406; 
        }

        return response($response,$status);
    } 
    
    //Displaying ViewAuditReport
    public function viewAuditReport(Request $request,$id)
    {
      try{    
            $fromDate =$request->fromDate;
            $toDate = $request->toDate;

            $result = DB::table('audits')
                ->where('audits.assetType','=',$id)
                ->where('auditDate','>=',$fromDate) 
                ->where('auditDate','<=', $toDate)
                ->join('departments','departments.id','=','audits.department')
                ->join('sections','sections.id','=','audits.section')
                ->join('assettypes','assettypes.id','=','audits.assetType')
                ->select('audits.*','audits.auditName',
                 'departments.department_name as department',
                 'sections.section as section','assettypes.assetType as assetType')
                ->get();

            if(!$result){
              throw new Exception("data not found");
            }
            $response=[
             "message" => "Audits List",
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
