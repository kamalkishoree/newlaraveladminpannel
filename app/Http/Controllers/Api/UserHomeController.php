<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;

class UserHomeController extends Controller
{
    public function Homepage(Request $request)
    {
        $data = [];
        $banner = Banner::whereNotNull('id')->get();
        $category = Category::whereNotNull('id')->get();
        $data['banner'] = $banner;
        $data['category'] = $category;
        return response()->json([
            'status' => 200,
            'data'  =>$data
        ]);
    }
}
