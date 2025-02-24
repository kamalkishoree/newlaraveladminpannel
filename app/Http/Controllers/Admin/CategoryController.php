<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = Category::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
					
                        $edit = '<a href="'.route('category.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                        $delete = '<button class="custom-delete-btn remove-category" data-id="'.$row->id.'" data-action="'.route('category.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                ->addColumn('image_url', function($row){
                    if ($row->image_url == null or empty($row->image_url)) {
                    	$image = '<img src="/assets/admin/img/default-Category.png" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 50px;">';
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
                	if ($row->is_active == 1) {
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
        return view('admin.category.index');
	}

	public function create()
	{
		return view('admin.category.create');
	}

	public function store(Request $request)
	{
        
		$rules = [
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
        $messages = [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists. Please choose another.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'The slug must be unique. Please choose another.',
            'image_url.image' => 'The uploaded file must be an image.',
            'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
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
			$Category = Category::create($input);
			Toastr::success(__('Category Added Successfully'));
		    return redirect()->route('category.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to create Category record.'));
		    return redirect()->route('category.index');
		}
	}

	public function edit($id)
	{
		$category = Category::find($id);
		return view('admin.category.edit',compact('category'));
	}

	public function update(Request $request, $id)
	{

        $Category = Category::find($id);
		$rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($Category->id),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($Category->id),
            ],
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
        $messages = [
            'name.required' => 'The category name is required.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'The slug must be unique. Please choose another.',
            'image_url.image' => 'The uploaded file must be an image.',
            'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
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


	    	
		if (empty($input['image_url'])) {
			$input['image_url'] = $Category->image_url;
		}


		try {
			$Category->update($input);
            Toastr::success(__('Category updated Successfully'));
		    return redirect()->route('category.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to update Category record.'));
		    return redirect()->route('category.index');
		}
	}

	public function destroy()
	{

		     $id = request()->input('id');
			$getCategory = Category::find($id);
			if (Storage::disk('s3')->exists($getCategory->image_url)) {
				die('sss');
				Storage::disk('s3')->delete($getCategory->image_url);
			} 
			try {
				Category::find($id)->delete();
				return back()->with(Toastr::error(__('Category deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete Category'));
				return redirect()->route('Category.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{
		$Category = Category::find($request->id)->update(['is_active' => $request->status]);

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

		$Category = Category::find($request->id)->update([$request->field => $request->value]);

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
