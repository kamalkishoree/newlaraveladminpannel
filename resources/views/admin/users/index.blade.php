@extends('admin.layouts.master')

@section('page_title')
    {{ __('user.index.title') }}
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
					<h3 class="page-title">{{__('user.index.title')}}</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('users.index') }}">{{ __('user.index.title') }}</a>
						</li>
					</ul>
				</div>
                @if (Gate::check('user-create'))
                    <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <a href="{{ route('users.create') }}" class="btn custom-create-btn">{{ __('default.form.add-button') }}</a>
                        </div>                 
                    </div>
                @endif
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                <form  action="{{ route('coupon.index') }}" method="GET" enctype="multipart/form-data" >
                     
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
                                <th class="">{{ __('default.table.name') }}</th>
                                <th class="">{{ __('default.table.email') }}</th>
                                <th class="">{{ __('default.table.mobile') }}</th>
                                <th class="">{{ __('default.table.role') }}</th>
                                <th class="">{{ __('default.table.status') }}</th>
                                <th class="">{{ __('Created On') }}</th>
                                @if (Gate::check('user-edit') || Gate::check('user-delete'))
                                    <th class="">{{ __('default.table.action') }}</th>
                                @endif
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
                url:'{{ route('users.index') }}',
                type: "GET",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.custom_field = "custom_value"; // You can send any custom data like this
                }
                },
                columns: [
					{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {  data: 'image', name: 'image' },
                    {  data: 'name', name: 'name' },
                    {  data: 'email', name: 'email' },
                    {  data: 'mobile',  name: 'mobile' },
                    { data: 'role',  name: 'role' },
                    { data: 'status',  name: 'status'  },
                    {  data: 'created_at', name: 'created_at' },
                    @if (Gate::check('user-edit') || Gate::check('user-delete'))
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    @endif
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
            ]

            });
        });
    </script>

    <script type="text/javascript">
        $("body").on("click", ".remove-user", function() {
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
                url: `{{ route('users.status_update') }}`,
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
