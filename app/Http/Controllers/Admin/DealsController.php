<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Deal;
use Yajra\DataTables\Facades\DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class DealsController extends Controller
{
    public function index(Request $request)
	{

		if ($request->ajax()) {
            $data = Deal::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
        
            return DataTables::of($data)
                ->addIndexColumn() // This will automatically add a DT_RowIndex column
                ->addColumn('action', function ($row) {
                    $edit = '<a href="' . route('deal.edit', $row->id) . '" class="custom-edit-btn mr-1">
                                <i class="fe fe-pencil"></i>
                                ' . __('default.form.edit-button') . '
                            </a>';
                    $delete = '<button class="custom-delete-btn remove-deal" data-id="' . $row->id . '" data-action="' . route('deal.destroy') . '">
                                    <i class="fe fe-trash"></i>
                                    ' . __('default.form.delete-button') . '
                                </button>';
                    return $edit . ' ' . $delete;
                })
                ->addColumn('headline', fn ($row) => $row->headline)
                ->addColumn('sub_headline', fn ($row) => $row->sub_headline)
                ->addColumn('code', fn ($row) => $row->code)
                ->addColumn('description', function($row){
                    $maxLength = 50; // Limit description to 50 characters
                    $shortDescription = Str::limit(strip_tags($row->description), $maxLength, '...');
                    return $shortDescription;
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';
                    return "
                        <input type='checkbox' id='status_$row->id' class='check' onclick='changeUserStatus(event.target, $row->id);' $checked>
                        <label for='status_$row->id' class='checktoggle'>checkbox</label>
                    ";
                })
                ->rawColumns(['action', 'status'])
                ->editColumn('created_at', fn ($row) => date("jS M Y", strtotime($row->created_at)))
                ->editColumn('updated_at', fn ($row) => date("jS M Y", strtotime($row->updated_at)))
                ->escapeColumns([])
                ->make(true);
        }
        
        return view('admin.deals.index',compact('request'));
        
	}

	public function create()
	{
        $brands = Brand::OrderBy('id','asc')->get();
		return view('admin.deals.create',compact('brands'));
	}

	public function store(Request $request)
	{


        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'headline' => 'required|string|max:255',
            'sub_headline' => 'required|string|max:255',
            'target_url' => 'required|string|url',
            'clocking_url' => 'required|string|url',
            'expires_at' => 'required|date',
            'description' => 'nullable|string',
            'sharing_message' => 'nullable|string',
            'code' => 'required|string|max:255|unique:deals,code',
        ];
        
        $messages = [
            'brand_id.required' => 'The brand ID is required.',
            'brand_id.exists' => 'The selected brand does not exist.',
            'headline.required' => 'The deals headline is required.',
            'headline.string' => 'The deals headline must be a string.',
            'headline.max' => 'The sub headline cannot exceed 255 characters.',
            'sub_headline.required' => 'The deals headline is required.',
            'sub_headline.string' => 'The deals sub headline must be a string.',
            'sub_headline.max' => 'The sub headline cannot exceed 255 characters.',
            'target_url.required' => 'The deals target URL is required.',
            'target_url.url' => 'The deals target URL must be a valid URL.',
            'clocking_url.required' => 'The deals clocking URL is required.',
            'clocking_url.url' => 'The deals clocking URL must be a valid URL.',
            'expires_at.required' => 'The expiration date is required.',
            'expires_at.date' => 'The expiration date must be a valid date.',
            'description.string' => 'The description must be a string.',
            'sharing_message.string' => 'The description must be a string.',
            'code.required' => 'The deals code is required.',
            'code.string' => 'The deals code must be a string.',
            'code.max' => 'The deals code cannot exceed 255 characters.',
        ];
        
        $this->validate($request, $rules, $messages);
        $input = request()->all();

     
     try {
	    $deals = Deal::create($input);
			Toastr::success(__('deals Added Successfully'));
		    return redirect()->route('deal.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to create deals record.'));
		    return redirect()->route('deal.index');
		}
	}

	public function edit($id)
	{
		$deal = Deal::find($id);
        $brands = Brand::OrderBy('id','asc')->get();
		return view('admin.deals.edit',compact('deal','brands'));
	}

	public function update(Request $request, $id)
	{

        $deals = Deal::find($id);
     
        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'headline' => 'required|string|max:255',
            'sub_headline' => 'required|string|max:255',
            'target_url' => 'required|string|url',
            'clocking_url' => 'required|string|url',
            'expires_at' => 'required|date',
            'description' => 'nullable|string',
            'sharing_message' => 'nullable|string',
            'code' => 'required|string|max:255',
        ];
        
        $messages = [
            'brand_id.required' => 'The brand ID is required.',
            'brand_id.exists' => 'The selected brand does not exist.',
            'headline.required' => 'The deals headline is required.',
            'headline.string' => 'The deals headline must be a string.',
            'headline.max' => 'The sub headline cannot exceed 255 characters.',
            'sub_headline.required' => 'The deals headline is required.',
            'sub_headline.string' => 'The deals sub headline must be a string.',
            'sub_headline.max' => 'The sub headline cannot exceed 255 characters.',
            'target_url.required' => 'The deals target URL is required.',
            'target_url.url' => 'The deals target URL must be a valid URL.',
            'clocking_url.required' => 'The deals clocking URL is required.',
            'clocking_url.url' => 'The deals clocking URL must be a valid URL.',
            'expires_at.required' => 'The expiration date is required.',
            'expires_at.date' => 'The expiration date must be a valid date.',
            'description.string' => 'The description must be a string.',
            'sharing_message.string' => 'The description must be a string.',
            'code.required' => 'The deals code is required.',
            'code.string' => 'The deals code must be a string.',
            'code.max' => 'The deals code cannot exceed 255 characters.',
        ];
    
      

        $this->validate($request, $rules, $messages);
		//$file = $request->file('image_url');
//$url = '';

        //  if(!is_null($file))
        //    {
        //        $fileName = time() . '_' . $file->getClientOriginalName();
		// 	   $path = $file->storeAs('website/Categories/images', $fileName, [
		// 		'disk' => 's3',
		// 		'visibility' => 'public'
		// 	]);        
		//     $url = Storage::disk('s3')->url($path);
        //   }
          $input = request()->all();
        //   $input['image_url'] = $url;


	    	
		// if (empty($input['image_url'])) {
		// 	$input['image_url'] = $deals->image_url;
		// }

        if($request->has('is_new'))
        {
       
            $input['is_new'] =  $request->is_new  == 'on' ? 1:0;

        }

        if($request->has('is_top'))
        {
            $input['is_top'] =  $request->is_top  == 'on' ? 1:0;

        }

        if($request->has('is_feature'))
        {
            $input['is_feature'] =  $request->is_feature  == 'on'  ? 1:0;

        }

		try {

			$deals->update($input);
            Toastr::success(__('deals updated Successfully'));
		    return redirect()->route('deal.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to update deals record.'));
		    return redirect()->route('deal.index');
		}
	}

	public function destroy(Request $request)
    {
		    $id = request()->input('id');
			$getbrand = Deal::find($id);
			if (Storage::disk('s3')->exists($getbrand->image_url)) {
				Storage::disk('s3')->delete($getbrand->image_url);
			} 
			try {
				Deal::find($id)->delete();
				return back()->with(Toastr::error(__('deals deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete deals'));
				return redirect()->route('deal.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{

		$deals = Deal::find($request->id)->update(['status' => $request->status]);

		if($request->status == 1)
        {
            return response()->json(['message' => 'Status activated successfully.']);
        }
        else{
            return response()->json(['message' => 'Status deactivated successfully.']);
        }  
	}



	public function status_update_custom(Request $request)
	{

		$deals = Deal::find($request->id)->update([$request->field => $request->value]);

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
