<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = Product::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
					
                        $edit = '<a href="'.route('product.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                        $delete = '<button class="custom-delete-btn remove-product" data-id="'.$row->id.'" data-action="'.route('product.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                ->addColumn('image_url', function($row){
                    if ($row->image_url == null or empty($row->image_url)) {
                    	$image = '<img src="/assets/admin/img/default-product.png" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 50px;">';
                    }else{
                    	$image = '<img src="'.$row->image_url.'" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 60px; height: 45px;">';
                    }
                    return $image;
                })
                ->addColumn('name', function($row){
                    return $row->name;
                })

                ->addColumn('sku', function($row){
                    return $row->sku;
                })

                ->addColumn('slug', function($row){
                    return $row->slug;
                })
                ->addColumn('description', function($row){
                    return $row->description;
                })
                ->addColumn('short_description', function($row){
                    return $row->short_description;
                })
                // ->addColumn('category_id', function($row){
                //     return $row->category_id;
                // })
                // ->addColumn('brand_id', function($row){
                //     return $row->brand_id;
                // })
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
        return view('admin.product.index');
	}

	public function create()
	{
        $brands = Brand::whereNotNull('id')->get();
        $categories = Category::whereNotNull('id')->get();
		return view('admin.product.create',compact('brands','categories'));
	}

	public function store(Request $request)
	{

		$rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            // 'short_description' => 'nullable|string|max:500',
           // 'sku' => 'required|string|max:512|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'targer_url' => 'nullable',
        ];
        $messages = [
            'name.required' => 'The product name is required.',
            'name.max' => 'The product name may not exceed 255 characters.',
            'slug.required' => 'A unique slug is required.',
            'slug.unique' => 'This slug already exists. Please choose another.',
            
           // 'sku.required' => 'The SKU is required for the product.',
           // 'sku.unique' => 'The SKU must be unique.',
            'category_id.required' => 'The product must belong to a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'brand_id.exists' => 'The selected brand does not exist.',
            'image.image' => 'The main image must be a valid image file.',
            'image.mimes' => 'Only JPEG, PNG, JPG, and GIF formats are allowed for images.',
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
			$product = Product::create($input);
			Toastr::success(__('product Added Successfully'));
		    return redirect()->route('product.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to create product record.'));
		    return redirect()->route('product.index');
		}
	}

	public function edit($id)
	{
		$brands = Brand::whereNotNull('id')->get();
        $categories = Category::whereNotNull('id')->get();
		return view('admin.product.edit',compact('brands','categories'));
	}

	public function update(Request $request, $id)
	{

        $product = Product::find($id);
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:512',
                Rule::unique('categories')->ignore($product->id),
            ],

            'sku' => [
                'required',
                'string',
                'max:512',
                Rule::unique('categories')->ignore($product->id),
            ],
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',  // Logo should be an image file
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id', // Ensure category exists in the categories 
        ];
        $messages = [
            'name.required' => 'The product name is required.',
            'name.string' => 'The product name must be a string.',
            'name.max' => 'The product name cannot exceed 255 characters.',
            'slug.required' => 'The product slug is required.',
            'slug.unique' => 'This product slug has already been taken.',
            'slug.max' => 'The slug cannot exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
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
			$input['image_url'] = $product->image_url;
		}


		try {
            
			$product->update($input);
            Toastr::success(__('product updated Successfully'));
		    return redirect()->route('product.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to update product record.'));
		    return redirect()->route('product.index');
		}
	}

	public function destroy()
	{
		     $id = request()->input('id');
			$getproduct = Product::find($id);
			if (Storage::disk('s3')->exists($getproduct->image_url)) {
				Storage::disk('s3')->delete($getproduct->image_url);
			} 
			try {
				Product::find($id)->delete();
				return back()->with(Toastr::error(__('product deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete product'));
				return redirect()->route('product.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{
		$product = Product::find($request->id)->update(['status' => $request->status]);

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

		$product = Product::find($request->id)->update([$request->field => $request->value]);

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

    public function customtest()
    {

       $str ="daabcbaabcbc";
       $array = explode('abc',$str);
       return implode('',$array);

    }

}
