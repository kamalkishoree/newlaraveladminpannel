<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
   
      public function searchBrands(Request $request)
      {

           pr($request->all());
         
      }

}
