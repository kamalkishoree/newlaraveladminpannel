<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function brandList(Request $request)
    {

      
        if($request->has('category_id') && !is_null($request->category_id))
        {
            $data['info'] = "brand listed by category id : " . $request->category_id;
            $brand = Brand::where('category_id',$request->category_id)->paginate(10);
        }
        else{
            $data['info'] = "All Brands";
            $brand = Brand::whereNotNull('id')->paginate(10);
        }
        $data['brands'] = $brand;
        return response()->json([
            'message' =>'success',
            'data' =>$data,
        ],200);
    }
}
