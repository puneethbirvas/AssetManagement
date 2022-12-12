<?php

namespace App\Http\Controllers;

use App\Models\scrapAsset;
use App\Exports\ScrapAssetsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use DB;
use Str;
use Storage;

class ScrapAssetController extends Controller
{
    public function store(Request $request)
    {
       try{                
            $scrapAsset = new scrapAsset;
                
            $scrapAsset->scrapType = $request->scrapType;
            $scrapAsset->department = $request->department;
            $scrapAsset->section = $request->section;
            $scrapAsset->assetType = $request->assetType;
            $scrapAsset->assetName = $request->assetName;
           
            //imageStoring
            $image = $request->scrapAprovalLetter;
            if($image){  // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $scrapAsset->scrapAprovalLetter = $imagePath;
            }

            $scrapAsset->user='Admin';

            $scrapAsset->save();
            $response = [
                'success' => true,
                'message' => $request->scrapType." Added successfully",
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
    
    public function showData()
    {
        try{    
            $scrapAsset = DB::table('scrap_assets')
                ->join('departments','departments.id','=','scrap_assets.department')
                ->join('sections','sections.id','=','scrap_assets.section')
                ->join('assettypes','assettypes.id','=','scrap_assets.assetType')
                ->join('assets','assets.id','=','scrap_assets.assetName')
                ->select('scrap_assets.id','departments.department_name as department',
                 'sections.section as section','assettypes.assetType as assetType',
                 'assets.assetName as assetName','scrap_assets.created_at as dateAndTime','scrap_assets.user')
               ->get();
          
            if(!$scrapAsset){
             throw new Exception("ScrapAsset not found");
            }

            $response=[
             "message" => "ScrapAsset List",
             "data" => $scrapAsset
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

    public function export()
    {
      $query = DB::table('scrap_assets')
        ->join('departments','departments.id','=','scrap_assets.department')
        ->join('sections','sections.id','=','scrap_assets.section')
        ->join('assettypes','assettypes.id','=','scrap_assets.assetType')
        ->join('assets','assets.id','=','scrap_assets.assetName')
        ->select('scrap_assets.id','departments.department_name as department',
         'sections.section as section','assettypes.assetType as assetType',
         'assets.assetName as assetName','scrap_assets.created_at as dateAndTime','scrap_assets.user')
        ->get();
  
      return Excel::download(new ScrapAssetsExport($query), 'ScrapAsset.xlsx');
    }
}
