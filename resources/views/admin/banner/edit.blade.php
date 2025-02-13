@extends('admin.layouts.master')

@section('page_title')
    {{__('Banner Edit')}}
@endsection

@push('css')
	<style>
		#output{
			height: 300px;
			width: 300px;
		}

	</style>
@endpush

@section('content')
    <form action="{{ route('banner.update',$banner->id) }}" method="POST" enctype="multipart/form-data">
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
                                    href="{{ route('banner.update',$banner->id) }}">{{ __('Update Banner') }}</a></li>
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
                                <img src="{{$banner->image_url}} width=400 height=400"/>
                            </div>
                            <div class="input-group mb-5">
                                <input type="file" id="image1" class="form-control" name="image_url">
                            </div>
                           </div>  <!-- /row end -->
                         


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
                                <input type="text" name="title" id="title" class="form-control @error('title') form-control-error @enderror" required="required" value="{{  $banner->title }}">

                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="description" class="required">{{ __('Description') }}:</label>
                                <input type="text" name="description" id="description" class="form-control @error('description') form-control-error @enderror"  required="required" value="{{$banner->description   }}">

                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                       
                            <div class="form-group">
                                <label for="redirect_url" class="required">{{ __('Redirect URL') }}:</label>
                                <input type="text" name="redirect_url" id="redirect_url" class="form-control @error('redirect_url') form-control-error @enderror"  required="required" value="{{ $banner->redirect_url  }}">
                            @error('redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>

                            <div class="form-group">
                                <label for="platform" class="required">{{ __('Platform') }}:</label>
                                <select  name="platform" id="redirect_url" class="form-control @error('platform') form-control-error @enderror"  required="required" value="{{  $banner->platform }}">
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
	document.addEventListener("DOMContentLoaded", function() {
	document.getElementById('button-image').addEventListener('click', (event) => {
		event.preventDefault();
		inputId = 'image1';
		window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
		});
	});

	// input
	let inputId = '';
	let output = 'output';

	// set file link
	function fmSetLink($url) {
	document.getElementById(inputId).value = $url;
	document.getElementById(output).src = $url;
	}
</script>
@endpush