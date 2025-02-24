<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = Campaign::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
					
                        $edit = '<a href="'.route('campaign.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                        $delete = '<button class="custom-delete-btn remove-campaign" data-id="'.$row->id.'" data-action="'.route('campaign.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                
                ->addColumn('user', function($row){
                    return $row->user->name;
                })
                ->addColumn('unique_p1_id', function($row){
                    return $row->unique_p1_id;
                })

                ->addColumn('brand_id', function($row){
                    return $row->brand->name;
                })
                ->addColumn('target_url', function($row){
                    return $row->brand->target_url.'&p1='.$row->unique_p1_id;
                })
                ->addColumn('campaign_id', function($row){
                    return $row->campaign_id;
                })
                ->addColumn('view', function($row){
                    return '<button class="custom-delete-btn view-campaign-data" data-id="'.$row->id.'" data-action="'.route('conversion.view').'">
							<i class="fe fe-eye"></i>
		                          '.__('view').'
							</button>';
                })
                ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
	            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
	            ->escapeColumns([])
                ->make(true);
        }
        return view('admin/campaign/index');
	}

		
	public function destroy()
	{
		     $id = request()->input('id');
			$getCategory = Campaign::find($id);
			
			try {
				Campaign::find($id)->delete();
				return back()->with(Toastr::error(__('Category deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete Category'));
				return redirect()->route('Category.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{
		$Category = Campaign::find($request->id)->update(['is_active' => $request->status]);

		if($request->is_status == 1)
        {
            return response()->json(['message' => 'Status activated successfully.']);
        }
        else{
            return response()->json(['message' => 'Status deactivated successfully.']);
        }  
	}



	public function status_update_custom(Request $request)
	{

		$Category = Campaign::find($request->id)->update([$request->field => $request->value]);

        if($request->field == 'is_feature')
        {
          $field = 'Feature';
        }
        elseif($request->field == 'is_new')
        {
            $field = 'New';
        }else{
            $field = 'Top';
        }

		if($request->value == 1)
        {
            return response()->json(['value'=>$request->value,'message' => $field. ' activated successfully.']);
        }
        else{
            return response()->json(['value'=>$request->value,'message' => $field .' deactivated successfully.']);
        }  
	}


}
