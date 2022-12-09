<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use Illuminate\Http\Request;
use App\Exports\untagAssetExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Database\QueryException;
use DB;

class UntagAssetController extends Controller
{
    public function update(Request $request,$id)
    {
        $get = DB::table('allocations')->where('assetName','=',$id)->first();
        $get = $get->id;
        $untag = Allocation::find($get);

        $untag->reasonForUntag = $request->reasonForUntag;
        $untag->tag = $request->tag;

        $untag->save();

        $response = [
            "data" => "untagged successfuly",
            "status" => 200
        ];
        $status = 200;

        return Response($response,$status);
    }

    public function showData(Request $request)
    {
      try{    
            $fromDate =$request->fromDate;
            $toDate = $request->toDate;

            $result = DB::table('allocations')
                    ->where('fromDate','>=',$fromDate) 
                    ->where('toDate','<=', $toDate)
                    ->where('reasonForUntag','!=',"null")
                    ->join('departments','departments.id','=','allocations.department')
                    ->join('sections','sections.id','=','allocations.section')
                    ->join('assettypes','assettypes.id','=','allocations.assetType')
                    ->join('assets','assets.id','=','allocations.assetName')
                    ->leftjoin('users as A','A.id','=','allocations.user')
                    ->leftjoin('users as B','B.id','=','allocations.empId')
                    ->select('allocations.*','departments.department_name as department',
                     'sections.section as  section','assettypes.assetType as assetType',
                     'assets.assetName as assetName','A.user_name as user',
                     'departments.department_name as userDepartment','B.employee_id as empId')  
                    ->get();
                    
            if(!$result){
              throw new Exception("data not found");
            }
            $response=[
             "message" => "UnTag Assets List",
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

    public function export(Request $request)
    {
        $fromDate =$request->fromDate;
        $toDate = $request->toDate;

        $query = DB::table('allocations')
            ->where('fromDate','>=',$fromDate) 
            ->where('toDate','<=', $toDate)
            ->where('reasonForUntag','!=',"null")
            ->join('assets','assets.id','=','allocations.assetName')
            ->join('sections','sections.id','=','allocations.section')
            ->join('users','users.id','=','allocations.user')
            ->select('allocations.id','sections.section as section', 
             'assets.assetName as assetName','assets.assetId as assetId',
             'users.employee_name as user')
            ->get();
       
        return Excel::download(new untagAssetExport($query), 'UnTagAssets.xlsx');
    }
}
