<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function brandList(Request $request,$category_id=NULL)
    {
        $data = [];
        if($category_id)
        {
            $brand = Brand::where('category_id',$category_id)->paginate(10);
        }
        else{
            $brand = Brand::whereNotNull('id')->paginate(10);
        }
        $data['brands'] = $brand;
        return response()->json([
            'message' =>'success',
            'data' =>$data,
        ],200);
    }
}
