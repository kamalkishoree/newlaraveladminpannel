<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function couponList(Request $request)
    {
        if($request->has('category_id') && !is_null($request->category_id))
        {
            $data['info'] = "Coupons listed by category id : " . $request->category_id;
            $brand = Coupon::where('category_id',$request->category_id)->paginate(10);
        }
        else{
            $data['info'] = "All Coupons";
            $brand = Coupon::whereNotNull('id')->paginate(10);
        }
        $data['Coupon'] = $brand;
        return response()->json([
            'message' =>'success',
            'data' =>$data,
        ],200);
    }


    public function couponDetails(Request $request)
    {
        $data = [];
        $data['coupon']=[];
        $coupon = Coupon::find($request->id);
        if($deal)
        {
            $data['coupon'] = $coupon;
        }
        return response()->json([
            'message' =>'success',
            'data' =>$data,
        ],200);
    }
}
