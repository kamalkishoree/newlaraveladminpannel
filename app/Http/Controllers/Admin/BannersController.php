<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class BannersController extends Controller
{
    function __construct()
	{
		
	}

	public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = Banner::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
					
                        $edit = '<a href="'.route('banner.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                        $delete = '<button class="custom-delete-btn remove-Banner" data-id="'.$row->id.'" data-action="'.route('banner.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                ->addColumn('image_url', function($row){
                    if ($row->image_url == null or empty($row->image_url)) {
                    	$image = '<img src="/assets/admin/img/default-Banner.png" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 50px;">';
                    }else{
                    	$image = '<img src="'.$row->image_url.'" class="w-50 rounded-circle img-fluid img-thumbnail" style="max-width: 60px; height: 45px;">';
                    }
                    return $image;
                })
                ->addColumn('title', function($row){
                    return $row->title;
                })
                ->addColumn('description', function($row){
                    return $row->description;
                })

                ->addColumn('redirect_url', function($row){
                    return $row->redirect_url;
                })

                ->addColumn('platform', function($row){
                    return $row->platform;
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
        return view('admin.banner.index');
	}

	public function create()
	{
		return view('admin.banner.create');
	}

	public function store(Request $request)
	{

		$rules = [
            'title' 			=> 'required',
			'description' 		=> 'required',
			'image_url' 		=> 'required',
			'redirect_url' 		=> 'required',
			'platform' 		    => ' required',
			//'staus' 		    =>  'required',
        ];

        $messages = [
            'title.required'    	=> __('Title Field is required.'),
            'description.required'  => __('Description Field is required.'),
            'image_url.email'    	=> __('Image Field is required.'),
            'redirect_url.unique'   => __('Redirect URL Field is required.'),
            'platform.required'    	=> __('Platform Field is required.'),
          //  'staus.required'    	=> __('Staus Field is required.'),
            
        ];

        $this->validate($request, $rules, $messages);
        $file = $request->file('image_url');
        $url = '';
       
         if(!is_null($file))
           {
               $fileName = time() . '_' . $file->getClientOriginalName();
			   $path = $file->storeAs('website/assets/images', $fileName, [
				'disk' => 's3',
				'visibility' => 'public'
			]);      
               $url = Storage::disk('s3')->url($path);
          }
          $input = request()->all();
          $input['image_url'] = $url;
		try {
			$Banner = Banner::create($input);
			Toastr::success(__('Banner Added Successfully'));
		    return redirect()->route('banner.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to create banner record.'));
		    return redirect()->route('banner.index');
		}
	}

	public function edit($id)
	{
		$banner = Banner::find($id);
		return view('admin.banner.edit',compact('banner'));
	}

	public function update(Request $request, $id)
	{
		$rules = [
            'title' 			=> 'required',
			'description' 		=> 'required',
			'image_url' 		=> 'required',
			'redirect_url' 		=> 'required',
			'platform' 		    => ' required',
			//'staus' 		    =>  'required',
        ];

        $messages = [
            'title.required'    	=> __('Title Field is required.'),
            'description.required'  => __('Description Field is required.'),
            'image_url'    	=> __('Image Field is required.'),
            'redirect_url.unique'   => __('Redirect URL Field is required.'),
            'platform.required'    	=> __('Platform Field is required.'),
            //'staus.required'    	=> __('Staus Field is required.'),
            
        ];
        
        $this->validate($request, $rules, $messages);

	
		$file = $request->file('image_url');
        $url = '';
         if(!is_null($file))
           {
               $fileName = time() . '_' . $file->getClientOriginalName();
			   $path = $file->storeAs('website/assets/images', $fileName, [
				'disk' => 's3',
				'visibility' => 'public'
			]);        
		      $url = Storage::disk('s3')->url($path);
          }
          $input = request()->all();
          $input['image_url'] = $url;
	    	$Banner = Banner::find($id);
		if (empty($input['image_url'])) {
			$input['image_url'] = $Banner->image_url;
		}

		try {
			$Banner->update($input);
            Toastr::success(__('Banner updated Successfully'));
		    return redirect()->route('banner.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to update banner record.'));
		    return redirect()->route('banner.index');
		}
	}

	public function destroy()
	{

		     $id = request()->input('id');
			$getBanner = Banner::find($id);
			if (Storage::disk('s3')->exists($getBanner->image_url)) {
				Storage::disk('s3')->delete($getBanner->image_url);
			} 
			try {
				Banner::find($id)->delete();
				return back()->with(Toastr::error(__('Banner deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete banner'));
				return redirect()->route('banner.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{
		$Banner = Banner::find($request->id)->update(['status' => $request->status]);

		if($request->status == 1)
        {
            return response()->json(['message' => 'Status activated successfully.']);
        }
        else{
            return response()->json(['message' => 'Status deactivated successfully.']);
        }  
	}
}

   
  