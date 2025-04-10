<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ReferralController extends Controller
{
     public function handleReferral(Request $request){
        return Redirect::intended('quicksapp://referral-download?code='.$request->code);

     }
}
