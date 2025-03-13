<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffilateIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class AffilateIntegrationController extends Controller
{
    

   public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = AffilateIntegration::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
			
                        $edit = '<a href="'.route('affiliate.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                   
                        $delete = '<button class="custom-delete-btn remove-user" data-id="'.$row->id.'" data-action="'.route('affiliate.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                ->addColumn('provider_name', function($row){
                     return $row->provider_name??"N/A";
                })

                ->addColumn('base_url', function($row){
                  return $row->base_url??"N/A";
                })

                ->addColumn('api_key', function($row){
                  return $row->api_key??"N/A";
                })
                ->addColumn('client_id', function($row){
                  return $row->client_id??"N/A";
                })
                ->addColumn('client_secret', function($row){
                  return $row->client_secret??"N/A";
                })
                ->addColumn('headers', function($row){
                  return $row->headers??"N/A";
                })

                ->rawColumns(['action'])

	         
               ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
	            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
	            ->escapeColumns([])
                ->make(true);
        }
        return view('admin.affiliate.index');
	}

   public function create()
	{
		return view('admin.affiliate.create');
	}

	public function store(Request $request)
	{

		$rules = [
         'provider_name' 			=> 'required',
			    'api_key' 		=> 'required',
          'base_url' =>'required'
        ];

        $messages = [
            'provider_name.required'    		=> __('Provider name is a required field'),
            'api_key.required'    	=> __('API key name is a required field'),
            'base_url' =>__('Base Url is a required field.')
        ];
         $this->validate($request, $rules, $messages);
	   	$input = request()->all();

		try {
			$user = AffilateIntegration::create($input);
			Toastr::success(__('Data saved successfully.'));
		    return redirect()->route('affiliate.index');

		} catch (Exception $e) {
			Toastr::error(__('Data not saved.'));
		    return redirect()->route('affiliate.index');
		}
	}

	public function edit($id)
	{
		$affilateIntegration = AffilateIntegration::find($id);
		return view('admin.affiliate.edit',compact('affilateIntegration'));
	}

	public function update(Request $request, $id)
	{
         $rules = [
            'provider_name' 			=> 'required',
            'api_key' 		=> 'required',
         ];

        $messages = [
            'provider_name.required'    		=> __('Provider name is a required field'),
            'api_key.required'    	=> __('API key name is a required field'),
          
        ];
         $this->validate($request, $rules, $messages);
		$input = $request->all();
		$affilateIntegration = AffilateIntegration::find($id);

		try {
			$affilateIntegration->update($input);
         Toastr::success(__('Data Updated successfully.'));
         return redirect()->route('affiliate.index');
      } catch (Exception $e) {
         Toastr::error(__('Data not saved.'));
            return redirect()->route('affiliate.index');
      }
	}

	public function destroy()
	{
		$id = request()->input('id');
		$AffilateIntegration = AffilateIntegration::all();
			try {
				AffilateIntegration::find($id)->delete();
				return back()->with(Toastr::error(__('Api record deleted successfully.')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete this Api record.'));
				return redirect()->route('users.index')->with($error_msg);
			}
	}
	


}
