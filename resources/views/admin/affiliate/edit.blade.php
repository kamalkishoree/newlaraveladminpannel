@extends('admin.layouts.master')

@section('page_title')
    {{__('user.edit.title')}}
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
	<form method="post" action="{{ route('affiliate.update', $affilateIntegration->id) }}" enctype="multipart/form-data">
		@csrf()

		@csrf()

		<div class="page-header">
			<div class="card breadcrumb-card">
				<div class="row justify-content-between align-content-between" style="height: 100%;">
					<div class="col-md-6">
						<h3 class="page-title">{{__('Affiliate Provider')}}</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="{{ route('dashboard') }}">Dashboard</a>
							</li>
						
							<li class="breadcrumb-item active-breadcrumb">
								<a href="{{route('affiliate.index')}}">{{ __('Affiliate Integration Manager') }}</a>
							</li>
						</ul>
					</div>
					<div class="col-md-3">
						<div class="create-btn pull-right">
							<button type="submit" class="btn custom-create-btn">{{ __('default.form.save-button') }}</button>
						</div>
					</div>
				</div>
			</div><!-- /card finish -->	
		</div><!-- /Page Header -->

		<section class="crud-body">
			<div class="row">
				<div class="col-md-12">

					<div class="card">
						
						<div class="card-header">
							<h5 class="card-title">
                            Affiliate Integration Details
                            </h5>
						</div>
						
                        <div class="card-body">

<div class="row">
    <div class="col-md-12">

        <div class="card-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="provider_name" class="required">{{__('Provider Name ')}}:</label>
                            <input type="text" name="provider_name" id="provider_name" class="form-control @error('provider_name') form-control-error @enderror" required="required" value="{{$affilateIntegration->provider_name}}">

                            @error('provider_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

						<div class="form-group">
										<label for="base_url" class="required">{{__("Base Url")}}:</label>
                                        <input type="text" name="base_url" id="base_url" class="form-control @error('base_url') form-control-error @enderror"  value="{{$affilateIntegration->base_url}}">
										@error('base_url')
											<span class="text-danger">{{ $message }}</span>
										@enderror
						</div>

                        <div class="form-group">
                            <label for="api_key" class="required">{{__("Api Key")}}:</label>
                            <input type="text" name="api_key" id="api_key" class="form-control @error('api_key') form-control-error @enderror" required="required" value="{{$affilateIntegration->api_key}}">

                            @error('api_key')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="client_id" class="required">{{__("Client ID ")}}:</label>
                            <input type="text" name="client_id" id="client_id" class="form-control @error('client_id') form-control-error @enderror"  value="{{$affilateIntegration->client_id}}">
                                @error('client_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="client_secret" class="required">{{__("Client Secret")}}:</label>
                            <input type="text" name="client_secret" id="client_secret" class="form-control @error('client_secret') form-control-error @enderror"  value="{{$affilateIntegration->client_secret}}">
                            @error('client_secret')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="headers" class="required">{{__("Client Secret")}}:</label>
                            <input type="json" name="headers" id="headers" class="form-control @error('headers') form-control-error @enderror"  value="{{$affilateIntegration->headers}}">
                            @error('headers')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                    </div>
                </div>
            </div> <!-- card-body-end -->








    
    </div> <!-- col-md-12-end -->
</div> <!-- row-end -->		
</div>

                         <!-- card-body-end -->

					</div> <!-- card-end -->

				</div> <!-- col-md-12-end -->
            </div> <!-- row-end -->
		</section>
		
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