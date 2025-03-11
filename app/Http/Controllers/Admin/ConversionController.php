<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffilateIntegration;
use App\Models\Campaign;
use App\Models\Conversion;
use App\Services\TrackierService;
use Aws\Api\ApiProvider;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    public function view(Request $request){
       $campaign =  Campaign::find($request->id);
       if($campaign)
       {

          $affiliate_url  = $campaign->brand->target_url;
          if(str_contains($affiliate_url,'vcommission'))
          {

            $conversion = Conversion::where('unique_source_id',$request->unique_source_id)->first();
            if($conversion)
            {
               return response()->json(['status'=>'success','conversion'=>$conversion]);
            }
            else{
               $conversion = (new TrackierService())->getConversions($campaign->unique_source_id);
               $conversion = isset($conversion['conversions'][0])?$conversion['conversions'][0]:[];
               if(count($conversion)>0)
               {
                  $filteredArray = [
                     'user_id' => $campaign->user_id ?? null,
                     'click_id' => $conversion['click_id'] ?? null,
                     'unique_source_id' => $conversion['source'] ?? null,
                     'method' => $conversion['method'] ?? null,
                     'sale' => $conversion['sale'] ?? null,
                     'p1' => $conversion['p1'] ?? null,
                     'p2' => $conversion['p2'] ?? null,
                     'p3' => $conversion['p3'] ?? null,
                     'p4' => $conversion['p4'] ?? null,
                     'p5' => $conversion['p5'] ?? null,
                     'sub1' => $conversion['sub1'] ?? null,
                     'txn_id' => $conversion['txn_id'] ?? null,
                     'note' => $conversion['note'] ?? null,
                     'currency' => $conversion['currency'] ?? null,
                     'payout' => $conversion['payout'] ?? null,
                     'brand' => $conversion['brand'] ?? null,
                     'status' => $conversion['status'] ?? null,
                     'campaign_id' => $conversion['campaign_id'] ?? null,
                     'campaign_name' => $conversion['campaign_name'] ?? null,
                 ];

                  $conversion_data =  Conversion::create($filteredArray);
               //   if($conversion['status'] == 'approved')

               //   {

               //   }

                  
                  return response()->json(['status'=>'success','conversion'=>$conversion_data]);
               }
               return response()->json(['status'=>'success','conversion'=>[]]);
            }
            
          }
          elseif(str_contains($affiliate_url,'vcommisseeeion')){

          }
          else{

          }
       }
    }
}
