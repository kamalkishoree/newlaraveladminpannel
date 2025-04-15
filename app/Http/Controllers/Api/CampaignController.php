<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class CampaignController extends Controller
{
    
    public function createUserCampaign(Request $request)
    {

  
        $data = [];
        if($request->url)
        {

           $params = getUrlParams($request->url);
           $unique_id = (string) Str::uuid();
           $campaign =  Campaign::create([
                'campaign_id' => $params['campaign_id'],
                'pub_id' =>$params['pub_id'],
                'brand_id' => $request->brand_id,                  
                'user_id' =>  Auth::user()->id,
                'campaign_provider_id' => 1,
                'unique_source_id'  => isset($params['unique_source_id'])?$params['unique_source_id']:$unique_id,
                'conversion_status' => 0,
                'mode_type' => 'brand'
            ]);
            if($campaign)
            {
                $data['campaign'] = $campaign;
                return response()->json([
                    'message' =>'success',
                    'data' =>$data,
                ],200);
            }
            else{
                return response()->json([
                    'message' =>'failed',
                    'error' =>''
                ],400);
            }
          
        }
    }

    public function userCampaignAll(Request $request)
    {
        $start_date = $request->start_date??'';
        $end_date   = $request->end_date??'';
        $data = [];
           $campaign = Campaign::with('brand')->where('user_id',Auth::user()->id);
           if ($start_date && $end_date) {
               $campaign->whereBetween('created_at', [$start_date, $end_date]);
             }
           $campaign = $campaign->paginate(40);
           $data['campaign'] = $campaign;
                return response()->json([
                    'message' =>'success',
                    'data' =>$data,
             ],200);
    }


    public function campaignAll(Request $request)
    {

        $data = [];
        if($request->url)
        {

            $params = getUrlParams($request->url);
            $unique_id = (string) Str::uuid();
            
           $campaign =  Campaign::create([
                'campaign_id' => $params['campaign_id'],
                'pub_id' =>$params['pub_id'],
                'unique_p1_id' => $unique_id,
                'brand_id' => $request->brand_id,                  
                'user_id' =>  Auth::user()->id,
                'campaign_provider_id' => 1
            ]);

            if($campaign)
            {
                $data['campaign'] = $campaign;
                return response()->json([
                    'message' =>'success',
                    'data' =>$data,
                ],200);
            }
            else{
                return response()->json([
                    'message' =>'failed',
                    'error' =>''
                ],400);
            }
          
        }
    }

}
