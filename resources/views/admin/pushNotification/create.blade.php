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
        .emoji-picker-container {
            position: relative;
            width: 100%;
        }
        .emoji-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
            z-index: 1;
        }
        .emoji-picker {
            position: absolute;
            right: 0;
            bottom: 100%;
            margin-bottom: 5px;
            z-index: 1000;
            display: none;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .emoji-picker.show {
            display: block;
        }
        .emoji-item {
            display: inline-block;
            padding: 5px;
            cursor: pointer;
            font-size: 1.5em;
        }
        .emoji-item:hover {
            background: #f0f0f0;
        }
        emoji-picker {
            --background: white;
            --border-color: #ddd;
            --button-active-background: #f0f0f0;
            --button-hover-background: #f5f5f5;
            --category-font-color: #666;
            --indicator-color: #666;
            --input-border-color: #ddd;
            --input-font-color: #333;
            --num-columns: 8;
            --outline-color: #ddd;
            --skintone-border-radius: 50%;
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
                                <div class="emoji-picker-container">
                                    <input type="text" name="title" id="title" class="form-control @error('title') form-control-error @enderror" required="required" value="{{ old('title') }}">
                                    <button type="button" class="emoji-button" data-target="title">😊</button>
                                    <div class="emoji-picker" id="title-emoji-picker"></div>
                                </div>
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
                                    <div class="emoji-picker-container">
                                        <textarea type="text" name="description" id="description" class="form-control @error('description') form-control-error @enderror" required="required">{{ old('description') }}</textarea>
                                        <button type="button" class="emoji-button" data-target="description">😊</button>
                                        <div class="emoji-picker" id="description-emoji-picker"></div>
                                    </div>
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
                               <x-ckeditor name="email_description" id="editor1" label="Description" />
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

<script type="module">
    import { Picker, Database } from 'https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js';
    const database = new Database();
    await database.ready;
    console.log('Initializing emoji picker...');
    // Handle title field emoji picker
    const titleButton = $('.emoji-button[data-target="title"]');
    const titlePicker = titleButton.next('.emoji-picker')[0];
    const titleInput = $('#title')[0];
    const titleEmojiPicker = new Picker({
        dataSource: database
    });

    titlePicker.appendChild(titleEmojiPicker);
    titleEmojiPicker.addEventListener('emoji-click', event => {
        console.log('Title emoji selected:', event.detail);
        if (!titleInput) {
            console.error('Title input not found!');
            return;
        }
        titleInput.focus();
        const emojiChar = event.detail.unicode;
        const start = titleInput.selectionStart || 0;
        const end = titleInput.selectionEnd || 0;
        const value = titleInput.value;
        titleInput.value = value.slice(0, start) + emojiChar + value.slice(end);
        titleInput.setSelectionRange(start + emojiChar.length, start + emojiChar.length);
        $(titlePicker).hide();
    });

    titleButton.on('click', function(e) {
        console.log('Title emoji button clicked');
        e.preventDefault();
        e.stopPropagation();
        $(titlePicker).toggle();
    });
    // Handle description field emoji picker
    const descriptionButton = $('.emoji-button[data-target="description"]');
    const descriptionPicker = descriptionButton.next('.emoji-picker')[0];
    const descriptionInput = $('#description')[0];
    const descriptionEmojiPicker = new Picker({
        dataSource: database
    });

    descriptionPicker.appendChild(descriptionEmojiPicker);
    descriptionEmojiPicker.addEventListener('emoji-click', event => {
        console.log('Description emoji selected:', event.detail);
        if (!descriptionInput) {
            console.error('Description input not found!');
            return;
        }

        descriptionInput.focus();
        const emojiChar = event.detail.unicode;
        const start = descriptionInput.selectionStart || 0;
        const end = descriptionInput.selectionEnd || 0;
        const value = descriptionInput.value;
     
        
        descriptionInput.value = value.slice(0, start) + emojiChar + value.slice(end);
        descriptionInput.setSelectionRange(start + emojiChar.length, start + emojiChar.length);
        $(descriptionPicker).hide();
    });

    descriptionButton.on('click', function(e) {
        console.log('Description emoji button clicked');
        e.preventDefault();
        e.stopPropagation();
        $(descriptionPicker).toggle();
    });
    // Close pickers when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.emoji-button, .emoji-picker').length) {
            $('.emoji-picker').hide();
        }
    });
</script>

@endpush
