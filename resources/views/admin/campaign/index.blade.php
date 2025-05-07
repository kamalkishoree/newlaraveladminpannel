@extends('admin.layouts.master')

@section('page_title')
    {{ __('Category List') }}
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
					<h3 class="page-title">{{__('Campaign Details')}}</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('campaign.index') }}">{{ __('Campaign') }}</a>
						</li>
					</ul>
				</div>
                   {{-- <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <a href="{{ route('campaign.create') }}" class="btn custom-create-btn">{{ __('Add New Campaign') }}</a>
                        </div>                 
                    </div>--}}
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card p-5">



            <form  action="{{ route('campaign.index') }}" method="GET" enctype="multipart/form-data" >
                     
                     <div class="row align-items-end">
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
                                 <button type="submit"  class="btn btn-quicks">{{ __('SUBMIT') }}</button>
                         </div>
                     </div>
                     <form >  

                <div class="card-body p-0">
                    <table class="table table-hover table-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="">{{ __('default.table.sl') }}</th>
                                <th class="">{{ __('Quick ID') }}</th>
                                <th class="">{{ __('User Name') }}</th>
                                <th class="">{{ __('P1 ID') }}</th>
                                <th class="">{{ __('Campaign ID') }}</th>
                                <th class="">{{ __('Brand Title') }}</th>
                                <th class="">{{ __('Target URL') }}</th>
                                <th class="">{{ __('Created On') }}</th>
                                <th class="">{{ __('Action') }}</th>

                            </tr>user
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>



    <div id="conversion-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="conversion-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conversion-modal-title">Conversion Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>ID:</strong> <span id="conversion-modal-id"></span></li>
                    <li class="list-group-item"><strong>Order ID:</strong> <span id="conversion-modal-order-id"></span></li>
                    <li class="list-group-item"><strong>Unique Source ID:</strong> <span id="conversion-modal-unique-source-id"></span></li>
                    <li class="list-group-item"><strong>Sale Amount:</strong> <span id="conversion-modal-sale"></span></li>
                    <li class="list-group-item"><strong>Payout:</strong> <span id="conversion-modal-payout"></span></li>
                    <li class="list-group-item"><strong>Brand:</strong> <span id="conversion-modal-brand"></span></li>
                    <li class="list-group-item"><strong>Status:</strong> <span id="conversion-modal-status"></span></li>
                    <li class="list-group-item"><strong>Transaction ID:</strong> <span id="conversion-modal-txn-id"></span></li>
                    <li class="list-group-item"><strong>Note:</strong> <span id="conversion-modal-note"></span></li>
                </ul>
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
                url: "{{ route('campaign.index') }}",
                type: "GET",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.custom_field = "custom_value"; // You can send any custom data like this
                    }
                },

                columns: [
					{  data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {  data: 'quick_id', name: 'quick_id' },
                    {  data: 'user', name: 'user' },
                    {  data: 'unique_p1_id', name: 'unique_p1_id' },
                    {  data: 'campaign_id', name: 'campaign_id' },
                    {  data: 'brand_id', name: 'brand_id' },
                    {  data: 'target_url', name: 'target_url' },
                    {  data: 'created_at', name: 'created_at' },
                    {  data: 'view', name: 'view' },


                ],
            });
        });
    </script>

    <script type="text/javascript">
        $("body").on("click", ".remove-campaign", function(e) {
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
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `{{ route('campaign.status_update') }}`,
                type: 'GET',
                data: {
                    _token: _token,
                    id: id,
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




<script type="text/javascript">
    // Function to clear modal fields
    function clearConversionModal() {
        $("#conversion-modal-id").text('-');
        $("#conversion-modal-unique-source-id").text('-');
        $("#conversion-modal-order-id").text('');
        $("#conversion-modal-sale").text('-');
        $("#conversion-modal-payout").text('-');
        $("#conversion-modal-brand").text('-');
        $("#conversion-modal-status").text('-');
        $("#conversion-modal-txn-id").text('-');
        $("#conversion-modal-note").text('-');
    }

    $("body").on("click", ".view-campaign-data", function(e) {
        e.preventDefault();

        var id = $(this).attr('data-id');
        var unique_source_id = $(this).attr('data-unique_source_id');

        // Clear modal before making the request
        clearConversionModal();

        $.ajax({
            url: `{{ route('conversion.view') }}`,
            type: 'GET',
            data: {
                id: id,
                unique_source_id: unique_source_id
            },
            success: function(result) {
                if (result.status == 'success' && result.conversion) {
                    var data = result.conversion;
                    $("#conversion-modal-id").text(data.id);
                    $("#conversion-modal-order-id").text(data.order_id);
                    $("#conversion-modal-unique-source-id").text(data.unique_source_id);
                    $("#conversion-modal-sale").text(data.sale + ' ' + data.currency);
                    $("#conversion-modal-payout").text(data.payout + ' ' + data.currency);
                    $("#conversion-modal-brand").text(data.brand);
                    $("#conversion-modal-status").text(data.status);
                    $("#conversion-modal-txn-id").text(data.txn_id);
                    $("#conversion-modal-note").text(data.note);
                    toastr.success("Data loaded successfully.");
                } else {
                    toastr.error(result.message || "No data found");
                }
                $("#conversion-modal").modal("show");
            },
            error: function() {
                toastr.error("Something went wrong.");
                $("#conversion-modal").modal("show");
            }
        });
    });
</script>



@endpush
