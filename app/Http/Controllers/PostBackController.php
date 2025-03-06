<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostBackController extends Controller
{
    

    public function vCommissionpostBack(Request $request)
    {

        \Log::warning($request->fullUrl());
        \Log::warning('vCommissionpostBack');
        \Log::warning(['request' =>$request->all()]);
    }

}
