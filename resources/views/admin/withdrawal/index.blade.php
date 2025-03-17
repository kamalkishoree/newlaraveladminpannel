@extends('admin.layouts.master')

@section('page_title')
    {{ __('Withdrawal List') }}
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
					<h3 class="page-title">{{__('Withdrawals Details')}}</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('withdrawal.index') }}">{{ __('Withdrawal Request') }}</a>
						</li>
					</ul>
				</div>
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">


            <form  action="{{ route('withdrawal.index') }}" method="GET" enctype="multipart/form-data" >
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

                         <div class="form-group col-md-2">
                             <label for="status" class="">{{ __('STATUS') }}:</label>
                             <select name="status" id="status" class="form-control @error('status') form-control-error @enderror">
                                <option value="">Select Status</option>
                                <option <?=@$request->status == 'pending' ? 'selected' : '' ?> value="pending">Pending</option>
                                <option <?= @$request->status == 'approved' ? 'selected' : '' ?> value="approved">Approved</option>
                                <option <?= @$request->status == 'rejected' ? 'selected' : '' ?> value="rejected">Rejected</option>
                             </select>
                             @error('status')
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
                                <th class="">{{ __('User') }}</th>
                                <th class="">{{ __('Amount') }}</th>
                                <th class="">{{ __('Bank Account') }}</th>
                                <th class="">{{ __('Status') }}</th>
                                <th class="">{{ __('Created On') }}</th>
                                <th class="">{{ __('Action') }}</th>

                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

<!-- Bank Details Modal -->
<div class="modal fade" id="bankDetailModal" tabindex="-1" role="dialog" aria-labelledby="bankDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bankDetailModalLabel">Bank Account Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Bank Name:</strong> <span id="modal-bank-name"></span></p>
        <p><strong>Account Number:</strong> <span id="modal-account-number"></span></p>
        <p><strong>Branch:</strong> <span id="modal-branch"></span></p>
        <p><strong>IFSC Code:</strong> <span id="modal-ifsc"></span></p>
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
                url:"{{ route('withdrawal.index') }}",
                type: "GET",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.status = $('#status').val();
                    d.custom_field = "custom_value"; // You can send any custom data like this
                }
            },

      
                columns: [
					{  data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {  data: 'user', name: 'user' },
                    {  data: 'amount', name: 'amount' },
                    {  data: 'bank_account', name: 'bank_account' },
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

        $(document).on('click', '.change-status-withdrawal', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            let _token = $('meta[name="csrf-token"]').attr('content');
            let action = $(this).data('action');
            $.ajax({
                url: action,
                type: 'GET',
                data: {
                    _token: _token,
                    id: id,
                    status: status
                },
                success:function(response) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseJSON.message);   
                    setTimeout(function() {
                        location.reload();
                    }, 1000);                }
            });

        });


        $(document).on('click', '[data-toggle="modal"]', function() {
    var bankName = $(this).data('bank-name');
    var accountNumber = $(this).data('account-number');
    var branch = $(this).data('branch');
    var ifsc = $(this).data('ifsc');

    $('#modal-bank-name').text(bankName);
    $('#modal-account-number').text(accountNumber);
    $('#modal-branch').text(branch);
    $('#modal-ifsc').text(ifsc);
});
    </script>
@endpush
