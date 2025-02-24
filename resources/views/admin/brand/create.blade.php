@extends('admin.layouts.master')

@section('page_title')
    {{ __('Brand Create') }}
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
    <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Create Brand')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('brand.index') }}">{{ __('Brands') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('brand.create') }}">{{ __('Add new Brands') }}</a></li>
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
                                <input type="file" id="image1" class="form-control" name="image_url">
                            </div>
                           </div>  <!-- /row end -->


             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                               Brand's Details
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="required">{{ __('Brand Name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') form-control-error @enderror" required="required" value="{{ old('name') }}">

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug" class="required">{{ __('Slug') }}:</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') form-control-error @enderror"  required="required" value="{{ old('slug') }}">

                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="target_url" class="required">{{ __('Target URL') }}:</label>
                                <input type="text" name="target_url" id="target_url" class="form-control @error('target_url') form-control-error @enderror"  required="required" value="{{ old('target_url') }}">
                                @error('target_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <x-ckeditor name="description" id="description" label="Description"  />
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="category_id" class="required">{{ __('Category') }}:</label>
                                <select  name="category_id" id="category_id" class="form-control @error('category_id') form-control-error @enderror"  required="required" value="{{ old('category_id') }}">
                                <option value="">select category ...</option>
                                @foreach($categories as $category)   
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                                </select>
                                @error('category_id')
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
@endpush
