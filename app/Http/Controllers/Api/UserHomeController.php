<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class UserHomeController extends Controller
{
    public function Homepage(Request $request)
    {
        $data = [];
        $banner = Banner::whereNotNull('id')->get();
        $data['banner'] = $banner;
        
        return response()->json([
            'status' => 200,
            'data'  =>$data
        ]);
    }
}
