<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\PushNotificationJob;
use Carbon\Carbon;

class PushNotificationController extends Controller
{
    public function index(Request $request)
	{
		if ($request->ajax()) {
            $data = PushNotification::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
					
                        $edit = '<a href="'.route('pushNotification.edit', $row->id).'" class="custom-edit-btn mr-1">
                                    <i class="fe fe-pencil"></i>
                                        '.__('default.form.edit-button').'
                                </a>';
                        $delete = '<button class="custom-delete-btn remove-pushNotification" data-id="'.$row->id.'" data-action="'.route('pushNotification.destroy').'">
										<i class="fe fe-trash"></i>
		                                '.__('default.form.delete-button').'
									</button>';
                   
                    $action = $edit.' '.$delete;
                    return $action;
                })

                
                ->addColumn('title', function($row){
                    return $row->title;
                })
                ->addColumn('type', function($row){
                    return $row->type;
                })

                ->addColumn('users', function($row){
                    return $row->users;
                })
              
                ->addColumn('schedule_datetime', function($row){
                    return !is_null($row->schedule_datetime)?$row->schedule_datetime:'N/A';
                })

                ->addColumn('status', function($row){
                    if($row->status ==0)
                    {
                        $status = "Pending";
                        $class="btn-primary";

                    }
                    if($row->status == 1)
                    {
                        $status = "Success";
                        $class="btn-success";
                    }
                    else{
                        $status = "Failed";
                        $class="btn-danger";
                    }
                    return '<button class="btn '.$class.' ">'.$status.'</button>';
                })

                ->addColumn('view', function($row){
                    return '<button class="custom-delete-btn view-pushNotification-data" data-id="'.$row->id.'" data-action="'.route('conversion.view').'">
							<i class="fe fe-eye"></i>
		                          '.__('view').'
							</button>';
                })
                ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
	            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
	            ->escapeColumns([])
                ->make(true);
        }
        return view('admin/pushNotification/index',compact('request'));
	}

    public function create()
	{
        
         $users = User::whereNotNull('id')->orderBy('id', 'asc')->get();
         $selectedUsers = [];
		 return view('admin.pushNotification.create',compact('users','selectedUsers'));

	}

	public function store(Request $request)
	{
        if($request->has('type'))
        {
           
            if($request->type == "push")
            {

                die('sss');
                $rules = [
                    'title'=>'required',
                    //'image_url' => 'image|mimes:jpeg,png,jpg,gif,webp',
                    'description' =>  'required',
                ];
                $messages = [
                    'title' =>  'Title is a required Field',
                    'image_url.image' => 'The uploaded file must be an image.',
                    'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
                    'description' =>  'Decription is a required Field',

                ];
            }
          
            if($request->type == "email")
            {
                $rules = [
                    'title' =>  'required',
                    'subject' =>  'required',
                    'email_description' =>  'required',
                ];
                $messages = [
                    'title' =>  'Title is a required Field',
                    'subject' =>  'Subject is a required Field',
                    'email_description' =>  'Decription is a required Field',
                ];
            }
          
            if($request->type == "sms")
            {
                $rules = [
                    'title' =>  'required',
                    'message' =>  'required',
                ];
                $messages = [
                    'title' =>  'Title is a required Field',
                    'message' =>  'Message is a required Field',
                ];
            }
            
            
        }else{
     
            $rules = [
                'title'=>'required',
                'image_url' => 'image|mimes:jpeg,png,jpg,gif,webp',
                'description' =>  'required',
            ];
            $messages = [
                'title' =>  'Title is a required Field',
                'image_url.image' => 'The uploaded file must be an image.',
                'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
                'description' =>  'Decription is a required Field',

            ];
        }
         $this->validate($request, $rules, $messages);
         $file = $request->file('image_url');
         $url = '';
         $input = request()->all();
         if(!is_null($file))
           {
                $fileName = time() . '_' . $file->getClientOriginalName();
			    $path = $file->storeAs('website/PushNotification/images', $fileName, [
				'disk' => 's3',
				'visibility' => 'public'
			]);      
               $url = Storage::disk('s3')->url($path);
             
          }
            $input['image_url'] = $url;
        try {
			$pushNotification = PushNotification::create($input);
            
            // Check if schedule_datetime is set
            if (!empty($pushNotification->schedule_datetime)) {
                // Convert schedule_datetime to Carbon instance
                $scheduleTime = Carbon::parse($pushNotification->schedule_datetime);
                
                // If schedule time is in the future, dispatch the job with delay
                if ($scheduleTime->isFuture()) {
                    PushNotificationJob::dispatch($pushNotification)
                        ->delay($scheduleTime);
                } else {
                    // If schedule time is in the past, dispatch immediately
                    PushNotificationJob::dispatch($pushNotification);
                }
            } else {
                // If no schedule time, dispatch immediately
                PushNotificationJob::dispatch($pushNotification);
            }
            
			Toastr::success(__('Push Notification Created Successfully'));
		    return redirect()->route('pushNotification.index');

		} catch (Exception $e) {
			Toastr::error($e->getMessage());
		    return redirect()->route('pushNotification.index');
		}
	}

	public function edit($id)
	{
        $users = User::whereNotNull('id')->orderBy('id', 'asc')->get();
        $selectedUsers = [];
		$pushNotification = PushNotification::find($id);
		return view('admin.pushNotification.edit',compact('pushNotification','users','selectedUsers'));
	}

	public function update(Request $request, $id)
	{
        $pushNotification = PushNotification::find($id);
        if($request->has('type'))
        {
            if($request->type == "push")
            {
                $rules = [
                    'title'=>'required',
                    'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
                    'description' =>  'required',
                ];
                $messages = [
                    'title' =>  'Title is a required Field',
                    'image_url.image' => 'The uploaded file must be an image.',
                    'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
                    'description' =>  'Decription is a required Field',

                ];
            }
            if($request->type == "email")
            {
                $rules = [
                    'title' =>  'required',
                    'subject' =>  'required',
                    'email_description' =>  'required',
                ];
                $messages = [
                    'title' =>  'Title is a required Field',
                    'subject' =>  'Subject is a required Field',
                    'email_description' =>  'Decription is a required Field',
                ];
            }
            if($request->type == "sms")
            {
                $rules = [
                    'title' =>  'required',
                    'message' =>  'required',
                ];
                $messages = [
                    'title' =>  'Title is a required Field',
                    'message' =>  'Message is a required Field',
                ];
            }
        }else{
            $rules = [
                'title'=>'required',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'description' =>  'required',
            ];
            $messages = [
                'title' =>  'Title is a required Field',
                'image_url.image' => 'The uploaded file must be an image.',
                'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
                'description' =>  'Decription is a required Field',

            ];
        }
         $this->validate($request, $rules, $messages);
             $file = $request->file('image_url');
         $url = '';
         if(!is_null($file))
           {
                $fileName = time() . '_' . $file->getClientOriginalName();
			    $path = $file->storeAs('website/PushNotification/images', $fileName, [
				'disk' => 's3',
				'visibility' => 'public'
			]);      
               $url = Storage::disk('s3')->url($path);
          }
           $input = request()->all();
           $input['image_url'] = !empty($url)?$url:$pushNotification->image_url;

        try {
			$pushNotification = PushNotification::create($input);
			Toastr::success(__('Push Notification Updated Successfully'));
		    return redirect()->route('pushNotification.index');

		} catch (Exception $e) {
			Toastr::error($e->getMessage());
		    return redirect()->route('pushNotification.index');
		}
	}

		
	public function destroy()
	{
		     $id = request()->input('id');
			$getCategory = PushNotification::find($id);
			
			try {
				PushNotification::find($id)->delete();
				return back()->with(Toastr::error(__('Category deleted')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('Failed to delete Category'));
				return redirect()->route('pushNotification.index')->with($error_msg);
			}
	}
	
	public function status_update(Request $request)
	{
		$pushNotification = PushNotification::find($request->id)->update(['is_active' => $request->status]);
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
		$pushNotification = PushNotification::find($request->id)->update([$request->field => $request->value]);
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
