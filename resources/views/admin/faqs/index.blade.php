@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">FAQs Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFaqModal">
                            <i class="fe fe-plus"></i> Add New FAQ
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="filter">Filter</button>
                            <button class="btn btn-secondary" id="reset">Reset</button>
                        </div>
                    </div>
                    <table id="faqsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Status</th>
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

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" role="dialog" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFaqModalLabel">Add New FAQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addFaqForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="question">Question</label>
                        <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="answer">Answer</label>
                        <textarea class="form-control" id="answer" name="answer" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit FAQ Modal -->
<div class="modal fade" id="editFaqModal" tabindex="-1" role="dialog" aria-labelledby="editFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFaqModalLabel">Edit FAQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFaqForm">
                <input type="hidden" id="edit_faq_id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_question">Question</label>
                        <textarea class="form-control" id="edit_question" name="question" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_answer">Answer</label>
                        <textarea class="form-control" id="edit_answer" name="answer" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#faqsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('faq.index') }}",
            data: function(d) {
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'question', name: 'question'},
            {data: 'answer', name: 'answer'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        dom: '<"top"Bfr>t<"bottom"lp>',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                filename: 'FAQs_List_' + moment().format('YYYY-MM-DD')
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                filename: 'FAQs_List_' + moment().format('YYYY-MM-DD')
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-danger',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                filename: 'FAQs_List_' + moment().format('YYYY-MM-DD')
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-info',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            }
        ]
    });

    // Filter and Reset buttons
    $('#filter').click(function() {
        table.draw();
    });

    $('#reset').click(function() {
        $('#statusFilter').val('');
        table.draw();
    });

    // Add FAQ
    $('#addFaqForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('faq.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addFaqModal').modal('hide');
                table.draw();
                toastr.success(response.message);
                $('#addFaqForm')[0].reset();
            },
            error: function(xhr) {
                toastr.error('Something went wrong!');
            }
        });
    });

    // Edit FAQ
    $(document).on('click', '.edit-faq', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('faq.edit', '') }}/" + id,
            method: 'GET',
            success: function(response) {
                $('#edit_faq_id').val(response.id);
                $('#edit_question').val(response.question);
                $('#edit_answer').val(response.answer);
                $('#edit_status').val(response.status);
                $('#editFaqModal').modal('show');
            }
        });
    });

    // Update FAQ
    $('#editFaqForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_faq_id').val();
        $.ajax({
            url: "{{ route('faq.update', '') }}/" + id,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editFaqModal').modal('hide');
                table.draw();
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error('Something went wrong!');
            }
        });
    });

    // Delete FAQ
    $(document).on('click', '.delete-faq', function() {
        var id = $(this).data('id');
        if(confirm('Are you sure you want to delete this FAQ?')) {
            $.ajax({
                url: "{{ route('faq.destroy', '') }}/" + id,
                method: 'delete',
                success: function(response) {
                    table.draw();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toastr.error('Something went wrong!');
                }
            });
        }
    });

    // Change Status
    $(document).on('click', '.change-faq-status', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        if(confirm('Are you sure you want to change the FAQ status?')) {
            $.ajax({
                url: "{{ route('faq.status.update') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    table.draw();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toastr.error('Something went wrong!');
                }
            });
        }
    });
});
</script>
@endpush 