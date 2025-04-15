<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;
class DealController extends Controller
{
        public function dealList(Request $request)
        {
            if($request->has('category_id') && !is_null($request->category_id))
            {
                $data['info'] = "Deals listed by category id : " . $request->category_id;
                $brand = Deal::where('category_id',$request->category_id)->paginate(10);
            }
            else{
                $data['info'] = "All Deal";
                $brand = Deal::whereNotNull('id')->paginate(10);
            }
            $data['deal'] = $brand;
            return response()->json([
                'message' =>'success',
                'data' =>$data,
            ],200);
        }
    
    
        public function dealDetails(Request $request)
        {
            $data = [];
            $data['deal']=[];
            $deal = Deal::find($request->id);
            if($deal)
            {
                $deal['deal'] = $deal;
            }
            return response()->json([
                'message' =>'success',
                'data' =>$data,
            ],200);
        }
}
    