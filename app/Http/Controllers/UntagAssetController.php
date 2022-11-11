<?php

namespace App\Http\Controllers;

use App\Models\UntagAsset;
use Illuminate\Http\Request;
use DB;

class UntagAssetController extends Controller
{
    public function store(Request $request)
    {
        try{
            $untagAsset = new UntagAsset;
            
            $untagAsset->department = $request->department;
            $untagAsset->section  = $request->section ;
            $untagAsset->assetType = $request->assetType;
            $untagAsset->assetName = $request->assetName;
            $untagAsset->resonForUntag = $request->resonForUntag;
            $untagAsset->tag = $request->tag;
          
            $untagAsset->save();

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

    public function showData()
    {
      try{    
            // $fromDate =$request->fromDate;
            // $toDate = $request->toDate;

            $result = DB::table('untag_assets')
                    // ->where('fromDate','>=',$fromDate) 
                    // ->where('toDate','<=', $toDate)
                    ->join('sections','sections.id','=','untag_assets.section')
                    ->join('assets','assets.id','=','untag_assets.assetName')
                    ->select('sections.section as  section','assets.assetName as assetName',
                       'assets.assetId')  
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
}
