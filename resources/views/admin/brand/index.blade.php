@extends('admin.layouts.master')

@section('page_title')
    {{ __('Brand List') }}
@endsection

@push('css')
    <style>
        .table tr td {
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
					<h3 class="page-title">{{__('Brands Details')}}</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('brand.index') }}">{{ __('Brands') }}</a>
						</li>
					</ul>
				</div>
                    <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <a href="{{ route('brand.create') }}" class="btn custom-create-btn">{{ __('Add New Brands') }}</a>
                        </div>                 
                    </div>
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">


            <form  action="{{ route('brand.index') }}" method="GET" enctype="multipart/form-data" >
            <div class="card-body">

                     <div class="row">
                         <div class="form-group col-md-2">
                             <label for="start_date" class="required">{{ __('START DATE') }}:</label>
                             <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') form-control-error @enderror" value="{{ @$request->start_date }}"/>
                             @error('start_date')
                                 <span class="text-danger">{{ $message }}</span>
                             @enderror
                         </div>
     
                         <div class="form-group col-md-2">
                             <label for="end_date" class="required">{{ __('END DATE') }}:</label>
                             <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') form-control-error @enderror" value="{{ @$request->end_date }}"/>
                             @error('end_date')
                                 <span class="text-danger">{{ $message }}</span>
                             @enderror
                         </div>
     
                         <div class="form-group col-md-2 pt-4">
                                 <button type="submit"  class="btn custom-create-btn">{{ __('SUBMIT') }}</button>
                         </div>
                     </div>
                     <form >  
                    <table class="table table-hover table-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="">{{ __('default.table.sl') }}</th>
                                <th class="">{{ __('default.table.image') }}</th>
                                <th class="">{{ __('Brand Name') }}</th>
                                <th class="">{{ __('Slug') }}</th>
                                <th class="">{{ __('Description') }}</th>
                                <th class="">{{ __('status') }}</th>
                                <th class="">{{ __('Created On') }}</th>
                                <th class="">{{ __('default.table.action') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection




@push('scripts')
    <script>
        $(function() {
            $('#table').DataTable({
                processing: true,
                responsive: false,
                serverSide: true,
                order: [
                    [0, 'desc']
                ],
          
                ajax: {
                url:"{{ route('brand.index') }}",
                type: "GET",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.custom_field = "custom_value"; // You can send any custom data like this
                }
            },

                columns: [
					{  data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {  data: 'image_url', name: 'image_url' },
                    {  data: 'name', name: 'name' },
                    {  data: 'slug', name: 'slug' },
                    {  data: 'description', name: 'description' },
                    {  data: 'status', name: 'status' },                    
                    {  data: 'created_at', name: 'created_at' },
                    {  data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: '<"top"Bfr>t<"bottom"lp>', 
                buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn btn-danger'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-info'
                }
            ],
            lengthMenu: [10, 25, 50, 100], // Page length options
            pageLength: 10 // Default page length
            });
        });
    </script>

    <script type="text/javascript">
        $("body").on("click", ".remove-brand", function(e) {
            e.preventDefault();
            var current_object = $(this);
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "error",
                showCancelButton: true,
                dangerMode: true,
                cancelButtonClass: '#DD6B55',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Delete!',
            }, function(result) {
                if (result) {
                    var action = current_object.attr('data-action');
                    var token = jQuery('meta[name="csrf-token"]').attr('content');
                    var id = current_object.attr('data-id');

                    $('body').html("<form class='form-inline remove-form' method='POST' action='" + action + "'></form>");
                    $('body').find('.remove-form').append( '<input name="_method" type="hidden" value="post">');
                    $('body').find('.remove-form').append('<input name="_token" type="hidden" value="' + token + '">');
                    $('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id + '">');
                    $('body').find('.remove-form').submit();
                }
            });
        });
    </script>


    <script type="text/javascript">
        function changeUserStatus(_this, id) {
            var status = $(_this).prop('checked') == true ? 1 : 0;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `{{ route('brand.status_update') }}`,
                type: 'GET',
                data: {
                    _token: _token,
                    id: id,
                    status: status
                },
                success: function(result) {
					if(status == 1){
                    	toastr.success(result.message);
                	}else{
                    	toastr.error(result.message);
                	} 
                }
            });
        }
    </script>
@endpush
