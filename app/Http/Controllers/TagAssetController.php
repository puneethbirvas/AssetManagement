<?php

namespace App\Http\Controllers;

use App\Models\tagAsset;
use Illuminate\Http\Request;
use App\Models\Asset;
use DB;

class TagAssetController extends Controller
{
    public function store(Request $request)
    {
        try{
            $tagAsset = new tagAsset;
            
            $tagAsset->tagAssetType = $request->tagAssetType;
            $tagAsset->assetId = $request->assetId;
            $tagAsset->department = $request->department;
            $tagAsset->section  = $request->section ;
            $tagAsset->assetType = $request->assetType;
            $tagAsset->assetName = $request->assetName;
            $tagAsset->scanRfidNo = $request->scanRfidNo;
            $tagAsset->rfidNo = $request->rfidNo;
          
            $tagAsset->save();

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

    public function selectAssetId()
    {
        try{
            $selectAssetId = DB::table('assets');

            if(!$selectAssetId){
                throw new Exception("AssetId not found");
            }else{   

                $selectAssetId=DB::table('assets')->get('assetId');
                
                $response = [
                    'success' => true,
                    'data' => $selectAssetId,
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

    public function getAssetId( $id)
    { 
       try{ 
            $asset=Asset::find($id);
        
            if(!$asset){
                throw new Exception("data not found");
            }else{
                $asset = DB::table('assets')
                    ->where('assets.id','=',$id)
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->select('departments.department_name as department','sections.section as 
                     section','assettypes.assetType as  assetype','assetName','assetId')
                    ->get();

                $response = [
                    'success' => true,
                    'data' => $asset         
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

}
