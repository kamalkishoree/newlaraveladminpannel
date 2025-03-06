<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserConversion;
use Illuminate\Http\Request;

class UserConversionController extends Controller
{
  public function fetchConversion(Request $request)
  {
      
     $user_conversion = UserConversion::where('unique_source_id')->first();
     if($user_conversion->empty())
     {
       die('sss');
     }

  }
}
