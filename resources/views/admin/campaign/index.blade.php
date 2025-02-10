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
                    <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <a href="{{ route('campaign.create') }}" class="btn custom-create-btn">{{ __('Add New Campaign') }}</a>
                        </div>                 
                    </div>
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <table class="table table-hover table-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="">{{ __('default.table.sl') }}</th>
                                <th class="">{{ __('P1 ID') }}</th>
                                <th class="">{{ __('Campaign ID') }}</th>
                                <th class="">{{ __('Brand ID') }}</th>
                                <th class="">{{ __('Target URL') }}</th>
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

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">My Modal Title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             X  <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                ajax: "{{ route('campaign.index') }}",
                columns: [
					{  data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {  data: 'unique_p1_id', name: 'unique_p1_id' },
                    {  data: 'campaign_id', name: 'campaign_id' },
                    {  data: 'brand_id', name: 'brand_id' },
                    {  data: 'target_url', name: 'target_url' },
                    {  data: 'view', name: 'view' },


                ],
            });
        });
    </script>

    <script type="text/javascript">
        $("body").on("click", ".remove-campaign", function() {
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
                url: `{{ route('campaign.status_update') }}`,
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





<script type="text/javascript">
        $("body").on("click", ".view-campaign-data", function() {
            var current_object = $(this);
            var id = $(this).attr('data-id');
            $.ajax({
                url: `{{ route('campaign.view') }}`,
                type: 'GET',
                data: {
                    _token: _token,
                    id: id,
                    status: status
                },
                success: function(result) {
					if(status == 1){
                        $("#myModal").body('kamal');
                        $("#myModal").modal('show');
                    	toastr.success(result.message);
                	}else{
                    	toastr.error(result.message);
                	} 
                }
            });


        });
</script>



@endpush
