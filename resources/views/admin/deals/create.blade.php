@extends('admin.layouts.master')

@section('page_title')
    {{ __('Deals Create') }}
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
    <form action="{{ route('deal.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    
                            
                
                <div class="col-md-6">
                        <h3 class="page-title">{{__('CREATE DEAL')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('deal.index') }}">{{ __('Deals') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('deal.create') }}">{{ __('Add new Deals') }}</a></li>
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
                {{--  <div class="col-md-6">
                            <div class="input-group mb-5">
                                <input type="file" id="image1" class="form-control" name="image_url">
                            </div>
                  </div>  <!-- /row end -->
                  --}}

             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                               Deal Form
                            </h5>
                        </div>

                    
                          <div class="card-body">
                            <div class="form-group">
                                <label for="headline" class="required">{{ __('HEADLINE') }}:</label>
                                <input type="text" name="headline" id="headline" class="form-control @error('headline') form-control-error @enderror" required="required" value="{{ old('headline') }}">

                                @error('headline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sub_headline" class="required">{{ __('SUB HEADLINE') }}:</label>
                                <input type="text" name="sub_headline" id="sub_headline" class="form-control @error('sub_headline') form-control-error @enderror" required="required" value="{{ old('sub_headline') }}">

                                @error('sub_headline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="code" class="required">{{ __('CODE') }}:</label>
                                <input type="text" name="code" id="code" class="form-control @error('code') form-control-error @enderror"  required="required" value="{{ old('code') }}"/>
                                @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="target_url" class="required">{{ __('TARGET URL') }}:</label>
                                <input type="text" name="target_url" id="target_url" class="form-control @error('target_url') form-control-error @enderror"  required="required" value="{{ old('target_url') }}">
                                @error('target_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="form-group">
                                <label for="clocking_url" class="required">{{ __('CLOCKING URL') }}:</label>
                                <input type="text" name="clocking_url" id="clocking_url" class="form-control @error('clocking_url') form-control-error @enderror"  required="required" value="{{ old('clocking_url') }}">
                                @error('clocking_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="description" class="required">{{ __('OTHER DETAILS') }}:</label>
                                <textarea type="text" name="description" id="description" class="form-control @error('description') form-control-error @enderror"  required="required" value="{{ old('description') }}"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sharing_message" class="required">{{ __('SHARING MESSAGE') }}:</label>
                                <input type="text" name="sharing_message" id="message" class="form-control @error('sharing_message') form-control-error @enderror"  required="required" value="{{ old('sharing_message') }}"/>
                                @error('sharing_message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="brand_id" class="required">{{ __("DEAL's BRAND") }}:</label>
                                <select  name="brand_id" id="brand_id" class="form-control @error('brand_id') form-control-error @enderror"  required="required" value="{{ old('brand_id') }}">
                                <option value="">select brands ...</option>
                                @foreach($brands as $brand)   
                                <option value="{{$brand->id}}" selected="true">{{$brand->name}}</option>
                                @endforeach
                                </select>
                                @error('brand_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="expires_at" class="required">{{ __('EXPIRE ON') }}:</label>
                                <input type="datetime-local" name="expires_at" id="message" class="form-control @error('expires_at') form-control-error @enderror"  required="required" value="{{ old('expires_at') }}"/>
                                @error('expires_at')
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
