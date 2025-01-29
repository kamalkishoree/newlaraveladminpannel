@extends('admin.layouts.master')

@section('page_title')
    {{__('currency.create.title')}}
@endsection

@push('css')
	<style>
		#output{
			width: 100%;
		}

	</style>
@endpush

@section('content')
	<form method="post" action="{{ route('sms.store') }}" enctype="multipart/form-data">
		@csrf()

		<div class="page-header">
			<div class="card breadcrumb-card">
				<div class="row justify-content-between align-content-between" style="height: 100%;">
					<div class="col-md-6">
						<h3 class="page-title">{{__('SMS MANAGER')}}</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="{{ route('dashboard') }}">Dashboard</a>
							</li>
							<li class="breadcrumb-item">
								<a href="#">{{ __('Configuration') }}</a>
							</li>
							<li class="breadcrumb-item active-breadcrumb">
								<a href="#">{{ __('Sms manager') }}</a>
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
                            SMS Integration Details
							</h5>
						</div>
						
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">

									<div class="form-group">
										<label for="provider_name" class="required">{{__(' SMS PROVIDER ')}}:</label>
										<input type="text" name="provider_name" id="provider_name" class="form-control @error('name') form-control-error @enderror" required="required" value="{{old('provider_name')}}">

										@error('provider_name')
											<span class="text-danger">{{ $message }}</span>
										@enderror
									</div>

									<div class="form-group">
										<label for="sms_from" class="required">{{__("SMS From")}}:</label>
										<input type="text" name="sms_from" id="sms_from" class="form-control @error('code') form-control-error @enderror" required="required" value="{{old('sms_from')}}">

										@error('sms_from')
											<span class="text-danger">{{ $message }}</span>
										@enderror
									</div>

									<div class="form-group">
										<label for="api_key" class="required">{{__(" API KEY / EMAIL ")}}:</label>
										<input type="text" name="api_key" id="api_key" class="form-control @error('symbol') form-control-error @enderror" required="required" value="{{old('api_key')}}">

										@error('api_key')
											<span class="text-danger">{{ $message }}</span>
										@enderror
									</div>

									<div class="form-group">
										<label for="api_secret" class="required">{{__(" API Secret / Password ")}}:</label>
                                        <input type="text" name="api_secret" id="api_secret" class="form-control @error('api_secret') form-control-error @enderror" required="required" value="{{old('api_secret')}}">

                             
										@error('api_secret')
											<span class="text-danger">{{ $message }}</span>
										@enderror
									</div>


									<div class="form-group">
										<label for="app_id" class="required">{{__(" APP ID / Client ID ")}}:</label>
                                        <input type="text" name="app_id" id="app_id" class="form-control @error('app_id') form-control-error @enderror" required="required" value="{{old('app_id')}}">

                                            @error('app_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

								
									</div>

									
								</div>
							</div>
						</div> <!-- card-body-end -->

					</div> <!-- card-end -->

				</div> <!-- col-md-12-end -->
            </div> <!-- row-end -->
		</section>
		
	</form>
@endsection


@push('scripts')
<script>

</script>
@endpush