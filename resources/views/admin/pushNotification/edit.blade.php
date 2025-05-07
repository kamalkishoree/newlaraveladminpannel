@extends('admin.layouts.master')

@section('page_title')
    {{ __('Edit Push Notification') }}
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
            top: 100%;
            margin-top: 5px;
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
    <form action="{{ route('pushNotification.update',$pushNotification->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Update Push Notifactions')}}</h3>
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
                                    <input type="text" name="title" id="title" class="form-control @error('title') form-control-error @enderror" required="required" value="{{ $pushNotification->title }}">
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
                            <input type="datetime-local" value="{{$pushNotification->schedule_datetime}}" class="form-control" name="schedule_datetime">
                              @error('schedule_datetime')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="push_type">
                             
                            <div class="image_preview my-2">
                               <img src="{{$pushNotification->image_url}}" width="100" height="100" alt="#">
                            </div>
                            
                              <div class="input-group mb-5">
                                <label for="image_url" class="control-label">{{__("Image")}}</label>
                                    
                                    <input type="file" id="image1" class="form-control" name="image_url">
                                </div>



                                <div class="form-group">
                                <label for="title" class="required">{{ __('Redirect URL') }}:</label>
                                <div class="input-group mb-5">
                                    <input type="text" name="push_url_option_value" id="push_url_option_value" class="form-control @error('push_url_option_value') form-control-error @enderror" required="required" value="{{ $pushNotification->push_url_option_value }}">
                                </div>
                                @error('push_url_option_value')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                               </div>
  





                                <div class="form-group">
                                    <label for="description" class="required">{{ __('Description') }}:</label>
                                    <div class="emoji-picker-container">
                                        <textarea type="text" name="description" id="description" class="form-control @error('description') form-control-error @enderror" required="required">{{ $pushNotification->description }}</textarea>
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
                                <input type="text" id="subject" class="form-control" name="subject" value="{{$pushNotification->description}}" id="subject">
                                @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                               <x-ckeditor name="email_description" id="editor1" label="Description" />
                            </div>


                            <div class="sms_type d-none">
                            <div class="form-group">
                                <label for="message" class="required">{{ __('Message') }}:</label>
                                <textarea type="message" name="message" id="message" class="form-control @error('message') form-control-error @enderror"  required="required" value="{{$pushNotification->message}}">
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
<script type="module">
    import { Picker, Database } from 'https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js';
    
    document.addEventListener('DOMContentLoaded', async function() {
        const database = new Database();
        await database.ready;
        console.log('Initializing emoji picker...');

        // Initialize title emoji picker
        const titleButton = document.querySelector('.emoji-button[data-target="title"]');
        const titlePicker = document.getElementById('title-emoji-picker');
        const titleInput = document.getElementById('title');
        
        if (titleButton && titlePicker && titleInput) {
            const titleEmojiPicker = new Picker({
                dataSource: database
            });
            
            titlePicker.appendChild(titleEmojiPicker);
            
            titleEmojiPicker.addEventListener('emoji-click', event => {
                console.log('Title emoji selected:', event.detail);
                titleInput.focus();
                const emojiChar = event.detail.unicode;
                const start = titleInput.selectionStart || 0;
                const end = titleInput.selectionEnd || 0;
                const value = titleInput.value;
                titleInput.value = value.slice(0, start) + emojiChar + value.slice(end);
                titleInput.setSelectionRange(start + emojiChar.length, start + emojiChar.length);
                titlePicker.style.display = 'none';
            });

            titleButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                titlePicker.style.display = titlePicker.style.display === 'none' ? 'block' : 'none';
            });
        }

        // Initialize description emoji picker
        const descriptionButton = document.querySelector('.emoji-button[data-target="description"]');
        const descriptionPicker = document.getElementById('description-emoji-picker');
        const descriptionInput = document.getElementById('description');
        
        if (descriptionButton && descriptionPicker && descriptionInput) {
            const descriptionEmojiPicker = new Picker({
                dataSource: database
            });
            
            descriptionPicker.appendChild(descriptionEmojiPicker);
            
            descriptionEmojiPicker.addEventListener('emoji-click', event => {
                console.log('Description emoji selected:', event.detail);
                descriptionInput.focus();
                const emojiChar = event.detail.unicode;
                const start = descriptionInput.selectionStart || 0;
                const end = descriptionInput.selectionEnd || 0;
                const value = descriptionInput.value;
                descriptionInput.value = value.slice(0, start) + emojiChar + value.slice(end);
                descriptionInput.setSelectionRange(start + emojiChar.length, start + emojiChar.length);
                descriptionPicker.style.display = 'none';
            });

            descriptionButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                descriptionPicker.style.display = descriptionPicker.style.display === 'none' ? 'block' : 'none';
            });
        }

        // Close pickers when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.emoji-picker-container')) {
                document.querySelectorAll('.emoji-picker').forEach(picker => {
                    picker.style.display = 'none';
                });
            }
        });
    });
</script>

<script>
	var loadFileImageFront = function(event) {
		var output = document.getElementById('output');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>

<script>
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
