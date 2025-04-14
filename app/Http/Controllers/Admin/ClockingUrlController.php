<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Brand,Deal,Coupon,FinancialOffer,Campaign};
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ClockingUrlController extends Controller
{
    
   public function targetUrl(Request $request) {
     $campaign_id= '';
     $pub_id = '';
      if($request->has('brand'))
      {
        $data = Brand::find($request->brand);
        $mode_type = 'brand';
        if(!empty($data->target_url))
        {
          $target_url_params = getUrlParams($data->target_url);
          $campaign_id = @$target_url_params['campaign_id'];
          $pub_id = @$target_url_params['pub_id'];
        }
    
      }
      if($request->has('deal'))
      {
        $data = Deal::find($request->coupon);
        $mode_type = 'deal';
      }
      if($request->has('coupon'))
      {
        $data = Coupon::find($request->coupon);
        $mode_type = 'coupon';

      }
      if($request->has('financial-Offer'))
      {
        $data =  FinancialOffer::find($request->FinancialOffer);
        $mode_type = 'financial-Offer';

      }
      if($data)
      {
        $user_id = $userID = !empty($request->query('user_id'))?  $request->query('user_id'):NULL;
        if($userID)
        {
           $userID = '&user_Id='.$userID;
        }
        $uniqueSourceId = !empty($request->query('unique_source_id'))? $request->query('unique_source_id'):(string)Str::uuid();
        if(!empty($user_id) && !empty($campaign_id) && !empty($pub_id))
        {
            $campaign =  Campaign::create([
            'campaign_id' => $campaign_id,
            'pub_id' =>$pub_id,
            'brand_id' => $data->id,   
            'deal_id' => $data->deal_id,  
            'coupon_id' => $data->coupon_id,                 
            'financial_id' => $data->financial_id,  
            'user_id' =>  $user_id,
            'campaign_provider_id' => 1,
            'unique_source_id'  => $uniqueSourceId,
            'conversion_status' => 0,
            'mode_type' => $mode_type
           ]);
        }
        return Redirect::intended($data->target_url.'&unique_source_id='.$uniqueSourceId).$userID;
      }
   }
}
