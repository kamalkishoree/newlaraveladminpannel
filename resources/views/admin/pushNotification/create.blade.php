@extends('admin.layouts.master')

@section('page_title')
    {{ __('Push Notification') }}
@endsection

@push('css')
    <style>
        #output {
            height: 300px;
            width: 300px;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('pushNotification.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Create Push Notifactions')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('pushNotification.index') }}">{{ __('Push Notification') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('pushNotification.create') }}">{{ __('Add new Push Notification') }}</a></li>
                        </ul>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <button type="submit"
                                class="btn custom-create-btn">{{ __('default.form.save-button') }}</button>
                        </div>
                    </div>
                </div>
            </div><!-- /card finish -->	
        </div><!-- /Page Header -->
            <div class="row">
                  <div class="col-md-6">
                           
                            <div class="input-group mb-5">
                            {{--    <div class="custom-control custom-radio custom-control-inline">
                                   <input type="radio" id="push" name="type" class="custom-control-input email_type_select"  value ="push" checked>
                                <label class="custom-control-label" for="push">Push Notification</label>
                                </div>
                             <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="email" name="type" class="custom-control-input email_type_select " value ="email">
                                <label class="custom-control-label" for="email">Email</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="sms" name="type" class="custom-control-input email_type_select" value ="sms">
                                <label class="custom-control-label" for="sms">SMS</label>
                                </div>--}}
                            </div>

                 </div>  <!-- /row end -->
             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                            Push Notification's Details
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="title" class="required">{{ __('Title') }}:</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') form-control-error @enderror" required="required" value="{{ old('title') }}">

                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                            <div class="form-group">
                                <!--- Used Blade Component--->
                                 {{--   <x-select2 name="user_ids" id="user-select" :options="$users" :selected="$selectedUsers" multiple="true" placeholder="Select Users" />--}}
                            </div>

                            <div class="form-group">
                            <label for="schedule_datetime" class="control-label">{{__("Schedule Date")}}</label>
                            <input type="datetime-local" id="schedule_datetime" class="form-control" name="schedule_datetime" id="schedule_datetime">
                              @error('schedule_datetime')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="push_type">
                                <div class="input-group mb-5">
                                    <input type="file" id="image1" class="form-control" name="image_url">
                                </div>

                                <div class="form-group">
                                    <label for="description" class="required">{{ __('Description') }}:</label>
                                    <textarea type="text" name="description" id="description" class="form-control @error('description') form-control-error @enderror"  required="required">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="email_type d-none">
                                <div class="form-group">
                                <label for="subject" class="control-label">{{__("Subject")}}</label>
                                <input type="text" id="subject" class="form-control" name="subject" id="subject">
                                @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                               <x-ckeditor name="email_description" id="editor1" label="Description" description="" />
                            </div>


                            <div class="sms_type d-none">
                            <div class="form-group">
                                <label for="message" class="required">{{ __('Message') }}:</label>
                                <textarea type="message" name="message" id="message" class="form-control @error('message') form-control-error @enderror"  required="required" value="{{ old('message') }}">
                                </textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            </div>









                        </div> <!-- card-body-end -->
                    </div> <!-- card-end -->
                </div> <!-- col-md-4-end -->

            </div> <!-- row-end -->

        </div> <!-- card-body-end -->

    </form>
@endsection


@push('scripts')
<script>
	var loadFileImageFront = function(event) {
		var output = document.getElementById('output');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>

<script>
	// document.addEventListener("DOMContentLoaded", function() {
	// 	document.getElementById('button-image').addEventListener('click', (event) => {

	// 		event.preventDefault();
	// 		inputId = 'image1';
	// 		window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');

	// 	});
	// });

	// input
	// let inputId = '';
	// let output = 'output';

	// // set file link
	// function fmSetLink($url) {
	// 	document.getElementById(inputId).value = $url;
	// 	document.getElementById(output).src = $url;
	// }


    $('.email_type_select').click(function(e){
    if($(this).attr('id') == 'email')
    {
        $('.push_type').addClass('d-none');
        $('.sms_type').addClass('d-none');
        $('.email_type').removeClass('d-none');
    }
    else if($(this).attr('id') == 'sms')
    {
        $('.push_type').addClass('d-none');
        $('.email_type').addClass('d-none');
        $('.sms_type').removeClass('d-none');
    }
    else{
        $('.sms_type').addClass('d-none');
        $('.email_type').addClass('d-none');
        $('.push_type').removeClass('d-none');
    }
    });
    
</script>
@endpush
