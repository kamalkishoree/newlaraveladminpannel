@extends('admin.layouts.master')

@section('page_title')
    {{__('SMS Providers')}}
@endsection

@push('css')
	<style>
		.table tr td{
			vertical-align: middle;
		}
	</style>
@endpush

@section('content')
	 <!-- Page Header -->
	 <div class="page-header">
		<div class="card breadcrumb-card">
			<div class="row justify-content-between align-content-between" style="height: 100%;">
				<div class="col-md-6">
					<h3 class="page-title">{{__('SMS SERVICE PROVIDERS')}}</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('sms.index') }}">{{ __('SMS Providers') }}</a>
						</li>
					</ul>
				</div>
					<div class="col-md-3">
						<div class="create-btn pull-right">
							<a href="{{ route('sms.create') }}" class="btn custom-create-btn">{{ __('Add New Sms Providers') }}</a>
						</div>
					</div>
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->


	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">

                  {!! $dataTable->table() !!}
					{{--<table class="table table-hover table-center mb-0" id="table">
						<thead>
							<tr>
								<th class="">{{__('Providers Name')}}</th>
								<th class="">{{__('SMS From')}}</th>
								<th class="">{{__('API KEY / EMAIL')}}</th>
								<th class="">{{__('API Secret / Password')}}</th>
								<th class="">{{__('APP ID / Client ID')}}</th>
								<th class="">{{__('Status')}}</th>

							</tr>
						</thead>

						<tbody>
							
						</tbody>
					</table>--}}
				</div>
			</div>
		</div>
	</div>






@endsection
{!! $dataTable->scripts() !!}
@push('scripts')


@endpush