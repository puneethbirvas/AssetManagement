<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Str;


class LabelController extends Controller
{
    public function store(Request $request)
    {

       try{                
        $Label = new Label;
         
        $Label->Department  = $request->department ;
        $Label->selectSection = $request->selectSection;
        $Label->assetType = $request->assetType;
        $Label->selectAssetType = $request->selectAssetType;

        if($Label->selectAssetType == 'selectAsset'){
            $Label->selectAsset = $request->selectAsset;
        } 

        if($Label->selectAssetType == 'selectAssetId'){
            $Label->selectAssetId = $request->selectAssetId;
        }
        $Label->code = $request->code; 

        $getId = $this->getAssetName($request);

         // QrCode
        if($Label->code == 'qrCode'){
        $filename =  Str::random(10).'.png';
        $store = public_path().'/images/';
        base64_encode(QrCode::format('png')->size(100)->generate("$getId", $store. $filename));
        $Label->codeGenerator =  '/images/'.$filename;
        }
        // BarCode
        if($Label->code == 'barCode'){
        $name = 'barcode.png';
        Storage::disk('public')->put("$name" ,base64_decode(DNS1DFacade::getBarcodePNG("44453645656", "EAN13")));
        $Label->codeGenerator =  '/storage/'.$name;
        }
           
            $Label->save();
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

    public function getAssetName($id)
    { 

        $assetName = DB::table('assets')->where('id','=',$id)->get('assetId');

        return $assetName;
    }

     // Displaying data
    public function showData(Label $Label)
    {
 
        try{
            $Label = DB::table('labels')->select('id','department','selectSection','assetType','assetId','selectAssetType','selectAsset','selectAssetId','code','created_at','codeGenerator')->orderby('id','asc')->get();
 
            if(!$Label){
                throw new Exception("data not found");
            }else{
                $response = [
                     'success' => true,
                     'data' => $Label         
                ];
                $status = 201;   
            return response($response,$status);
            }
 
            }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404; 
         
        }
 
        return response($response,$status);
 
    }
    
    //destroy
    public function destroy(Label $Label, $id)
    {
        try{
            $Label = Label::find($id);
            if(!$Label){
                throw new Exception("data not found");
            }else{
                $Label->delete();

                $response = [
                     "message" => "Label deleted successfully",
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
        }
 
        return response($response,$status);
       
    }  
}
