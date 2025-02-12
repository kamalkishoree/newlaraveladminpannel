<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffilateIntegration;
use App\Models\Campaign;
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
            $requestParams['end'] = $campaign->created_at->format('Y-m-d');
            $requestParams['start'] = $campaign->created_at->format('Y-m-d');;
            $requestParams['p1'] = $campaign->unique_p1_id;

            $conversion = (new TrackierService())->getConversions($requestParams);
             pr($conversion);
          }
          elseif(str_contains($affiliate_url,'vcommisseeeion')){

          }
          else{

          }
       }
    }
}
