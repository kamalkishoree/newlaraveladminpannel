<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CampaignsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
  

     public function index(Request $request )
     {
        return view('admin.reports.index');
     }

     public function userExport() 
    {
      return Excel::download(new UsersExport, 'users_'.time().'.xlsx');
    }

    public function clickExport() 
    {
      return Excel::download(new CampaignsExport, 'click_'.time().'.xlsx');
    }

}
