<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffilateIntegration;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use App\Models\FinancialOffer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Exception;

class FinancialOfferController extends Controller
{
    public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = FinancialOffer::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
					
                        $edit = '<a href="'.route('financial-offer.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                        $delete = '<button class="custom-delete-btn remove-financial-offer" data-id="'.$row->id.'" data-action="'.route('financial-offer.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                ->addColumn('image_url', function($row){
                    if ($row->image_url == null or empty($row->image_url)) {
                    	$image = '<img src="/assets/admin/img/default-financial-offer.png" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 50px;">';
                    }else{
                    	$image = '<img src="'.$row->image_url.'" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 60px; height: 45px;">';
                    }
                    return $image;
                })
                ->addColumn('name', function($row){
                    return $row->name;
                })

                ->addColumn('slug', function($row){
                    return $row->slug;
                })
                ->addColumn('description', function($row){
                    $maxLength = 50; // Limit description to 50 characters
                    $shortDescription = Str::limit(strip_tags($row->description), $maxLength, '...');
                    return $shortDescription;
                })
                
                ->addColumn('status', function($row){
                	if ($row->status == 1) {
                		$current_status = 'Checked';
                	}else{
                		$current_status = '';
                	}
                    $status = "
                            <input type='checkbox' id='status_$row->id' id='user-$row->id' class='check' onclick='changeUserStatus(event.target, $row->id);' " .$current_status. ">
							<label for='status_$row->id' class='checktoggle'>checkbox</label>
                    ";
                    return $status;
                })

                ->rawColumns(['action', 'image_url'])
                ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
	            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
	            ->escapeColumns([])
                ->make(true);
        }
        return view('admin.financial-offer.index',compact('request'));
	}

	public function create()
	{

        $categories = FinancialCategory::whereNotNull('id')->get();
        $affiliate_partners = AffilateIntegration::select('id','provider_name')->whereNotNull('id')->get();
		return view('admin.financial-offer.create',compact('categories','affiliate_partners'));


	}

	public function store(Request $request)
	{


		$rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Logo should be an image file
            'category_id' => 'required|exists:categories,id', // Ensure category exists in the categories 
            'target_url' => 'required|url',
            'clocking_url' => 'required|url',
            'affiliate_network_id' => 'required|exists:affilate_integrations,id',

        ];
        $messages = [
            'name.required' => 'The financial-offer name is required.',
            'name.string' => 'The financial-offer name must be a string.',
            'name.max' => 'The financial-offer name cannot exceed 255 characters.',
            'slug.required' => 'The financial-offer slug is required.',
            'slug.unique' => 'This financial-offer slug has already been taken.',
            'slug.max' => 'The slug cannot exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'target_url.required' => 'The target URL is required.',
            'target_url.url' => 'The target URL must be a valid URL.',
            'clocking_url.required' => 'The cloaking URL is required.',
            'clocking_url.url' => 'The cloaking URL must be a valid URL.',
            'affiliate_network_id.required' => 'The affiliate network is required.',
        ];

        
        $this->validate($request, $rules, $messages);

        $file = $request->file('image_url');
        $url = '';
         if(!is_null($file))
           {
               $fileName = time() . '_' . $file->getClientOriginalName();
			   $path = $file->storeAs('website/Categories/images', $fileName, [
				'disk' => 's3',
				'visibility' => 'public'
			]);      
               $url = Storage::disk('s3')->url($path);
          }
          $input = request()->all();
          $input['image_url'] = $url;

    		try {
			$financialoffer = FinancialOffer::create($input);
			Toastr::success(__('Financial offer Added Successfully'));
		    return redirect()->route('financial-offer.index');

		} catch (Exception $e) {
            pr($e);
			Toastr::error(__('Failed to create financial offer record.'.$e->getMessage()));
		    //return redirect()->route('financial-offer.index');
		}
	}

	public function edit($id)
	{
		$financialoffer = FinancialOffer::find($id);
        $affiliate_partners = AffilateIntegration::select('id','provider_name')->whereNotNull('id')->get();
        $categories = FinancialCategory::whereNotNull('id')->get();

		return view('admin.financial-offer.edit',compact('financialoffer','categories','affiliate_partners'));
	}

	public function update(Request $request, $id)
	{

        $financialoffer = FinancialOffer::find($id);
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($financialoffer->id),
            ],
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Logo should be an image file
            'category_id' => 'required|exists:categories,id', // Ensure category exists in the categories 
        ];
        $messages = [
            'name.required' => 'The financial-offer name is required.',
            'name.string' => 'The financial-offer name must be a string.',
            'name.max' => 'The financial-offer name cannot exceed 255 characters.',
            'slug.required' => 'The financial-offer slug is required.',
            'slug.unique' => 'This financial-offer slug has already been taken.',
            'slug.max' => 'The slug cannot exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    
      

        $this->validate($request, $rules, $messages);
		$file = $request->file('image_url');
        $url = $financialoffer->image_url;

         if(!is_null($file))
           {
               $fileName = time() . '_' . $file->getClientOriginalName();
			   $path = $file->storeAs('website/Categories/images', $fileName, [
				'disk' => 's3',
				'visibility' => 'public'
			]);        
		    $url = Storage::disk('s3')->url($path);
          }
          $input = request()->all();
          $input['image_url'] = $url;


	    	
		if (empty($input['image_url'])) {
			$input['image_url'] = $financialoffer->image_url;
		}

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

			$financialoffer->update($input);
            Toastr::success(__('Financial-offer updated Successfully'));
		    return redirect()->route('financial-offer.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to update financial offer record.'));
		    return redirect()->route('financial-offer.index');
		}
	}

	public function destroy(Request $request)
	{


		     $id = request()->input('id');
			$getbrand = FinancialOffer::find($id);
			if (Storage::disk('s3')->exists($getbrand->image_url)) {
				Storage::disk('s3')->delete($getbrand->image_url);
			} 
			try {
				FinancialOffer::find($id)->delete();
				return back()->with(Toastr::error(__('financial-offer deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete financial-offer'));
				return redirect()->route('financial-offer.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{

        $financialoffer = FinancialOffer::find($request->id)->update(['status' => $request->status]);

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

		$financialoffer = FinancialOffer::find($request->id)->update([$request->field => $request->value]);

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
