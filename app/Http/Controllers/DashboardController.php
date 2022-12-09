<?php

namespace App\Http\Controllers;
use App\Models\Asset;
use App\Models\Allocation;
use App\Models\tagAsset;
use App\Models\scrapAsset;
use App\Models\RequestService;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function assetsCount()
    {
        $count = Asset::count();
        $counts['assets'] = $count;
        return $counts;

    }

    public function tagAssetsCount()
    {
        $count = tagAsset::count();
        $counts['tagAssets'] = $count;
        return $counts;

    }


    public function showData()
    {
      try{    
            $tagAsset = DB::table('tag_assets');

            if(!$tagAsset){
               throw new Exception("Asset not found");
            }else{
                $tagAsset = DB::table('tag_assets')
                    ->join('departments','departments.id','=','tag_assets.department')
                    ->join('sections','sections.id','=','tag_assets.section')
                    ->join('assettypes','assettypes.id','=','tag_assets.assetType')
                    ->join('assets','assets.id','=','tag_assets.assetName')
                    ->select('tag_assets.*','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName')
                    ->get();
                        
                $response=[
                    "message" => "Tag Assets List",
                    "data" => $tagAsset
                ];
                $status = 200; 
            } 
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

    public function untagCount()
    {
        $count = DB::table('allocations')->where('reasonForUntag','!=','null')->count();
        $counts['data']['untag'] = $count;
        return $counts;

    }

    public function warrantyDueCount()
    {
        $count = DB::table('assets')->whereBetween('warrantyEndDate', [now(), now()->addDays(7)])->count();
        $counts['warrantyDue'] = $count;
        return $counts;

    }

    // public function serviceeDueCount()
    // {
    //     $count = DB::table('amcs')->where('periodTo','!=','null')->count();
    //     $counts['data']['serviceeDue'] = $count;
    //     return $counts;

    // }

    public function amcDueCount()
    {
        $count = DB::table('amcs')->whereBetween('periodTo', [now(), now()->addDays(7)])->count();
        $counts['amcDue'] = $count;
        return $counts;

    }


    public function certificateDueCount()
    {
        $count = DB::table('certificates')->whereBetween('expireDate', [now(), now()->addDays(7)])->count();
        $counts['data']['certificateDue'] = $count;
        return $counts;

    }


    public function insuranceDueCount()
    {
        $count = DB::table('insurances')->whereBetween('periodTo', [now(), now()->addDays(7)])->count();
        $counts['data']['insurancenDue'] = $count;
        return $counts;

    }

    public function auditDueCount()
    {
        $count = DB::table('audits')->whereBetween('auditDate', [now(), now()->addDays(10)])->count();
        $counts['auditDue'] = $count;
        return $counts;

    }

    public function eolCount()
    {
        $count = DB::table('scrap_assets')->where('scrapType','=','EOL')->count();
        $counts['eol'] = $count;
        return $counts;

    }

    public function damageCount()
    {
        $count = DB::table('allocations')->where('reasonForUntag','=','defect')->count();
        $counts['damage'] = $count;
        return $counts;

    }

    public function transferCount()
    {
        $count = DB::table('assets')->where('transfer','=','1')->count();
        $counts['transfer'] = $count;
        return $counts;

    }

    public function inServiceCount()
    {
        $count = RequestService::count();
        $counts['inService'] = $count;
        return $counts;

    }


    public function salesCount()
    {
        $count = DB::table('allocations')->where('reasonForUntag','=','sale')->count();
        $counts['sales'] = $count;
        return $counts;

    }

    public function scrapCount()
    {
        $count = scrapAsset::count();
        $counts['scrapAssets'] = $count;
        return $counts;

    }
   

  
   
}