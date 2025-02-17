@extends('admin.layouts.master')

@section('page_title')
    {{ __('Banner Create') }}
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
    <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('create banner')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('banner.index') }}">{{ __('Banners') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('banner.create') }}">{{ __('Add new Banner') }}</a></li>
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
                    <div class="card">
                    <div class="card-header">
                            <h5 class="card-title">
                                Add Banner
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- <div class="input-group mb-5">
                                <input type="file" id="image1" class="form-control" name="image_url">
                            </div> -->

                            <form action="">
                            <div class="drop-zone">
                                <span class="drop-zone__prompt">Drop file or click to upload</span>
                                <!-- <input type="file" name="myFile" class="drop-zone__input" /> -->
                                <input type="file" id="image1" class="form-control drop-zone__input" name="image_url">
                            </div>
                            </form>

                           </div> 
                           </div> <!-- /row end -->
                    </div> 

             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                Banner's Details
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
                                <label for="description" class="required">{{ __('Description') }}:</label>
                                <input type="text" name="description" id="description" class="form-control @error('description') form-control-error @enderror"  required="required" value="{{ old('description') }}">

                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                       
                            <div class="form-group">
                                <label for="redirect_url" class="required">{{ __('Redirect URL') }}:</label>
                                <input type="text" name="redirect_url" id="redirect_url" class="form-control @error('redirect_url') form-control-error @enderror"  required="required" value="{{ old('redirect_url') }}">
                            @error('redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>

                            <div class="form-group">
                                <label for="platform" class="required">{{ __('Platform') }}:</label>
                                <select  name="platform" id="platform" class="form-control @error('platform') form-control-error @enderror"  required="required" value="{{ old('platform') }}">
                                <option value="1">WEB</option>
                                <option value="2">Mobile</option>
                                <option value="3">Both</option>
                            </select>
                           
                                @error('redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
</script>


<script>
  document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");

        dropZoneElement.addEventListener("click", (event) => {
          inputElement.click(); /*clicking on input element whenever the dropzone is clicked so file browser is opened*/
        });

        inputElement.addEventListener("change", (event) => {
          if (inputElement.files.length) {
            updateThumbnail(dropZoneElement, inputElement.files[0]);
          }
        });

        dropZoneElement.addEventListener("dragover", (event) => {
          event.preventDefault(); /*this along with prevDef in drop event prevent browser from opening file in a new tab*/
          dropZoneElement.classList.add("drop-zone--over");
        });
        ["dragleave", "dragend"].forEach((type) => {
          dropZoneElement.addEventListener(type, (event) => {
            dropZoneElement.classList.remove("drop-zone--over");
          });
        });
        dropZoneElement.addEventListener("drop", (event) => {
          event.preventDefault();
          console.log(
            event.dataTransfer.files
          ); /*if you console.log only event and check the same data location, you won't see the file due to a chrome bug!*/
          if (event.dataTransfer.files.length) {
            inputElement.files =
              event.dataTransfer.files; /*asigns dragged file to inputElement*/

            updateThumbnail(
              dropZoneElement,
              event.dataTransfer.files[0]
            ); /*thumbnail will only show first file if multiple files are selected*/
          }
          dropZoneElement.classList.remove("drop-zone--over");
        });
      });
      function updateThumbnail(dropZoneElement, file) {
        let thumbnailElement = dropZoneElement.querySelector(
          ".drop-zone__thumb"
        );
        /*remove text prompt*/
        if (dropZoneElement.querySelector(".drop-zone__prompt")) {
          dropZoneElement.querySelector(".drop-zone__prompt").remove();
        }

        /*first time there won't be a thumbnailElement so it has to be created*/
        if (!thumbnailElement) {
          thumbnailElement = document.createElement("div");
          thumbnailElement.classList.add("drop-zone__thumb");
          dropZoneElement.appendChild(thumbnailElement);
        }
        thumbnailElement.dataset.label =
          file.name; /*takes file name and sets it as dataset label so css can display it*/

        /*show thumbnail for images*/
        if (file.type.startsWith("image/")) {
          const reader = new FileReader(); /*lets us read files to data URL*/
          reader.readAsDataURL(file); /*base 64 format*/
          reader.onload = () => {
            thumbnailElement.style.backgroundImage = `url('${reader.result}')`; /*asynchronous call. This function runs once reader is done reading file. reader.result is the base 64 format*/
            thumbnailElement.style.backgroundPosition = "center";
          };
        } else {
          thumbnailElement.style.backgroundImage = null; /*plain background for non image type files*/
        }
      }  
</script>
@endpush
