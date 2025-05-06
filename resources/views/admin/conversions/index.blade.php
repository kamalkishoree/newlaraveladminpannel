@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Conversions') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Start Date') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->start_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('End Date') }}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->end_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-quicks btn-block" id="filter-btn">
                                    <i class="fe fe-filter"></i> {{ __('Filter') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="conversions-table">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Campaign') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Payout') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">{{ __('Conversion Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="conversion-details"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#conversions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('conversions.index') }}",
            data: function(d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'campaign', name: 'campaign'},
            {data: 'user', name: 'user'},
            {data: 'status', name: 'status'},
            {data: 'payout', name: 'payout'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    $('#filter-btn').click(function() {
        table.draw();
    });

    // View Conversion
    $(document).on('click', '.view-conversion', function() {
        var id = $(this).data('id');
        $.get("{{ url('admin/conversion/viewConversion') }}/" + id, function(response) {
            if(response.status === 'success') {
                var data = response.data;
                var html = `
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('Campaign') }}</th>
                                <td>${data.campaign.brand ? data.campaign.brand.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>{{ __('User') }}</th>
                                <td>${data.user ? data.user.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Status') }}</th>
                                <td>${data.status}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Payout') }}</th>
                                <td>${data.payout} ${data.currency}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Transaction ID') }}</th>
                                <td>${data.txn_id || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>${data.created_at}</td>
                            </tr>
                        </table>
                    </div>
                `;
                $('#conversion-details').html(html);
                $('#viewModal').modal('show');
            }
        });
    });

    // Delete Conversion
    $(document).on('click', '.delete-conversion', function() {
        var id = $(this).data('id');
        if(confirm("{{ __('Are you sure you want to delete this conversion?') }}")) {
            $.ajax({
                url: "{{ url('admin/conversion/conversion-delete') }}/" + id,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.draw();
                }
            });
        }
    });
});
</script>
@endpush 