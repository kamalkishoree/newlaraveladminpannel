<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Brand,Deal,Coupon,FinancialOffer};
use Illuminate\Support\Facades\Redirect;
class ClockingUrlController extends Controller
{
    
   public function targetUrl(Request $request) {
    
      if($request->has('brand'))
      {
        $data = Brand::find($request->brand);
      }
      if($request->has('deal'))
      {
        $data = Deal::find($request->coupon);

      }
      if($request->has('coupon'))
      {
        $data = Coupon::find($request->coupon);

      }
      if($request->has('financial-Offer'))
      {
        $data =  FinancialOffer::find($request->FinancialOffer);

      }
      if($data)
      {
        return Redirect::intended($data->target_url);
      }
   }
}
