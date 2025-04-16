@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tickets Management</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="start_date" placeholder="Start Date">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="end_date" placeholder="End Date">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="status">
                                <option value="">All Status</option>
                                <option value="inactive">Inactive</option>
                                <option value="opened">Opened</option>
                                <option value="approved">Approved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="filter">Filter</button>
                            <button class="btn btn-secondary" id="reset">Reset</button>
                        </div>
                    </div>
                    <table id="tickets-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Order ID</th>
                                <th>Order Amount</th>
                                <th>Order Status</th>
                                <th>Status</th>
                                <th>Image</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Ticket Modal -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" role="dialog" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTicketModalLabel">Ticket Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="ticket-details"></div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Ticket Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#tickets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.tickets.index') }}",
            data: function(d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.status = $('#status').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { data: 'order_id', name: 'order_id' },
            { data: 'order_amount', name: 'order_amount' },
            { data: 'order_status', name: 'order_status' },
            { data: 'status', name: 'status' },
            { 
                data: 'image_url', 
                name: 'image_url',
                render: function(data, type, row) {
                    if (data) {
                        return '<img src="' + data + '" alt="Ticket Image" class="img-thumbnail" style="max-width: 50px; cursor: pointer;" onclick="previewImage(\'' + data + '\')">';
                    }
                    return 'No Image';
                }
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#filter').click(function() {
        table.draw();
    });

    $('#reset').click(function() {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#status').val('');
        table.draw();
    });

    $(document).on('click', '.change-ticket-status', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        
        if (confirm('Are you sure you want to change the ticket status?')) {
            $.ajax({
                url: "{{ route('admin.tickets.status.update') }}",
                type: 'POST',
                data: {
                    id: id,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    table.draw();
                    alert('Ticket status updated successfully');
                }
            });
        }
    });

    $(document).on('click', '.view-ticket', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.tickets.show', '') }}/" + id,
            type: 'GET',
            success: function(response) {
                var ticket = response.data;
                var html = `
                    <div class="ticket-details">
                        <h4>${ticket.subject}</h4>
                        <p><strong>Order ID:</strong> ${ticket.order_id}</p>
                        <p><strong>Order Amount:</strong> ${ticket.order_amount}</p>
                        <p><strong>Order Status:</strong> ${ticket.order_status}</p>
                        <p><strong>Ticket Status:</strong> ${ticket.status}</p>
                        <p><strong>Description:</strong></p>
                        <p>${ticket.description}</p>
                        ${ticket.image_url ? `
                            <p><strong>Image:</strong></p>
                            <img src="${ticket.image_url}" alt="Ticket Image" class="img-fluid">
                        ` : ''}
                    </div>
                `;
                $('#ticket-details').html(html);
                $('#viewTicketModal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-ticket', function() {
        var id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this ticket?')) {
            $.ajax({
                url: "{{ route('admin.tickets.destroy', '') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    table.draw();
                    alert('Ticket deleted successfully');
                }
            });
        }
    });
});

function previewImage(imageUrl) {
    $('#previewImage').attr('src', imageUrl);
    $('#imagePreviewModal').modal('show');
}
</script>
@endpush 