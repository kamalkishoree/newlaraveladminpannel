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

				</div>
			</div>
		</div>
	</div>


@endsection
@push('scripts')
{!! $dataTable->scripts() !!}
@endpush