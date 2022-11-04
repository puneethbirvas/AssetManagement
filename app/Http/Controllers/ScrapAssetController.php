<?php

namespace App\Http\Controllers;

use App\Models\scrapAsset;
use Illuminate\Http\Request;
use DB;

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
            $image = $request->scrapAprovalLetter;  // your base64 encoded
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $image = str_replace($replace, '', $image); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(10).'.'.$extension;
            $imagePath = '/storage'.'/'.$imageName;
            Storage::disk('public')->put($imageName, base64_decode($image));

            $scrapAsset->scrapAprovalLetter = $imagePath;
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
        return DB::table('scrap_assets')->select('id', 'department','section','assetType','assetName','user')->orderby('id','asc')->get();
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
}
