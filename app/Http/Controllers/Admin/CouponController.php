<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Coupon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    

    public function index(Request $request)
	{

		if ($request->ajax()) {
          
            $data = Coupon::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
        
            return DataTables::of($data)
                ->addIndexColumn() // This will automatically add a DT_RowIndex column
                ->addColumn('action', function ($row) {
                    $edit = '<a href="' . route('coupon.edit', $row->id) . '" class="custom-edit-btn mr-1">
                                <i class="fe fe-pencil"></i>
                                ' . __('default.form.edit-button') . '
                            </a>';
                    $delete = '<button class="custom-delete-btn remove-coupon" data-id="' . $row->id . '" data-action="' . route('coupon.destroy') . '">
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
        
        return view('admin.coupon.index',compact('request'));
        
	}

	public function create()
	{
        $brands = Brand::OrderBy('id','asc')->get();
		return view('admin.coupon.create',compact('brands'));
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
            'code' => 'required|string|max:255|unique:coupons,code',
        ];
        
        $messages = [
            'brand_id.required' => 'The brand ID is required.',
            'brand_id.exists' => 'The selected brand does not exist.',
            'headline.required' => 'The coupon headline is required.',
            'headline.string' => 'The coupon headline must be a string.',
            'headline.max' => 'The sub headline cannot exceed 255 characters.',
            'sub_headline.required' => 'The coupon headline is required.',
            'sub_headline.string' => 'The coupon sub headline must be a string.',
            'sub_headline.max' => 'The sub headline cannot exceed 255 characters.',
            'target_url.required' => 'The coupon target URL is required.',
            'target_url.url' => 'The coupon target URL must be a valid URL.',
            'clocking_url.required' => 'The coupon clocking URL is required.',
            'clocking_url.url' => 'The coupon clocking URL must be a valid URL.',
            'expires_at.required' => 'The expiration date is required.',
            'expires_at.date' => 'The expiration date must be a valid date.',
            'description.string' => 'The description must be a string.',
            'sharing_message.string' => 'The description must be a string.',
            'code.required' => 'The coupon code is required.',
            'code.string' => 'The coupon code must be a string.',
            'code.max' => 'The coupon code cannot exceed 255 characters.',
        ];
        
        $this->validate($request, $rules, $messages);
        $input = request()->all();

     
     try {
	    $coupon = Coupon::create($input);
			Toastr::success(__('coupon Added Successfully'));
		    return redirect()->route('coupon.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to create coupon record.'));
		    return redirect()->route('coupon.index');
		}
	}

	public function edit($id)
	{
		$coupon = Coupon::find($id);
        $brands = Brand::OrderBy('id','asc')->get();
		return view('admin.coupon.edit',compact('coupon','brands'));
	}

	public function update(Request $request, $id)
	{

        $coupon = Coupon::find($id);
     
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
            'headline.required' => 'The coupon headline is required.',
            'headline.string' => 'The coupon headline must be a string.',
            'headline.max' => 'The sub headline cannot exceed 255 characters.',
            'sub_headline.required' => 'The coupon headline is required.',
            'sub_headline.string' => 'The coupon sub headline must be a string.',
            'sub_headline.max' => 'The sub headline cannot exceed 255 characters.',
            'target_url.required' => 'The coupon target URL is required.',
            'target_url.url' => 'The coupon target URL must be a valid URL.',
            'clocking_url.required' => 'The coupon clocking URL is required.',
            'clocking_url.url' => 'The coupon clocking URL must be a valid URL.',
            'expires_at.required' => 'The expiration date is required.',
            'expires_at.date' => 'The expiration date must be a valid date.',
            'description.string' => 'The description must be a string.',
            'sharing_message.string' => 'The description must be a string.',
            'code.required' => 'The coupon code is required.',
            'code.string' => 'The coupon code must be a string.',
            'code.max' => 'The coupon code cannot exceed 255 characters.',
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
		// 	$input['image_url'] = $coupon->image_url;
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

			$coupon->update($input);
            Toastr::success(__('coupon updated Successfully'));
		    return redirect()->route('coupon.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to update coupon record.'));
		    return redirect()->route('coupon.index');
		}
	}

	public function destroy()
	{

		     $id = request()->input('id');
			$getbrand = Coupon::find($id);
			if (Storage::disk('s3')->exists($getbrand->image_url)) {
				Storage::disk('s3')->delete($getbrand->image_url);
			} 
			try {
				Coupon::find($id)->delete();
				return back()->with(Toastr::error(__('coupon deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete coupon'));
				return redirect()->route('coupon.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{

		$coupon = Coupon::find($request->id)->update(['status' => $request->status]);

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

		$coupon = Coupon::find($request->id)->update([$request->field => $request->value]);

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
