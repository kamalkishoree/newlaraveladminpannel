<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

     public function index()
     {
        return view('homepage.coming-soon');
     }

     public function getPage($page)
     { 
            if($page =='privacy-policy' )
            {
               $page = 'privacy-policy';
            }

            else if($page =='contact' )
            {
                $page = 'contact';
            }

            else if($page =='terms-conditions')
            {
                 $page = "terms-conditions";
            }
            return view('homepage.'.$page);
     }

}
