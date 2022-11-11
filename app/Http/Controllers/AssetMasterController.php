<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AssetMasterController extends Controller
{

  public function showData($id)
  {
    try{    
        
      $result = DB::table('assets')
        ->where('assets.assetType','=',$id)
        ->join('departments','departments.id','=','assets.department')
        ->join('sections','sections.id','=','assets.section')
        ->join('assettypes','assettypes.id','=','assets.assetType')
        ->select('departments.department_name as department','sections.section as 
          section','assettypes.assetType as  assetype','assetName','manufaturer','poNo',
         'assetModel','warrantyStartDate')
        ->get();

      if(!$result){
        throw new Exception("data not found");
      }

      $response=[
        "message" => "Assets List",
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

