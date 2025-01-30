<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SmsProviderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\smsRequest;
use App\Models\SmsProvider;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
  public function index()
  {
    return view('admin.config.index');
  }

  public function smsManager(SmsProviderDataTable $dataTable, Request $request)
  {
    return $dataTable->render('admin.config.sms.index');
  }


  public function smsCreate()
  {
    return view('admin.config.sms.create');
  }


  public function smsStore(smsRequest $request)
  {

    $validated = $request->validated();       
	try {
        $input = $request->all();
        $smsProvider = SmsProvider::create($input);
        Toastr::success(__('Provider added Successfully.'));
        return redirect()->route('sms.index');
    } catch (Exception $e) {
        Toastr::error(__('There is an error! Please try later!.'));
        return redirect()->route('users.index');
    }
   
  }

}
