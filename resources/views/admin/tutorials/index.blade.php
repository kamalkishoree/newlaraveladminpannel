@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tutorials Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('tutorial.create') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Add New Tutorial
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="type">
                                <option value="{{NULL}}">All Types</option>
                                <option value="video">Video</option>
                                <option value="image">Image</option>
                                <option value="document">Document</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="is_active">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="filter">Filter</button>
                            <button class="btn btn-secondary" id="reset">Reset</button>
                        </div>
                    </div>
                    <table id="tutorials-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Expiry Date</th>
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

<!-- View Tutorial Modal -->
<div class="modal fade" id="viewTutorialModal" tabindex="-1" role="dialog" aria-labelledby="viewTutorialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTutorialModalLabel">Tutorial Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="tutorial-details"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#tutorials-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('tutorial.index') }}",
            data: function(d) {
                d.type = $('#type').val();
                d.is_active = $('#is_active').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'status' },
            { data: 'expiry_date', name: 'expiry_date' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#filter').click(function() {
        table.draw();
    });

    $('#reset').click(function() {
        $('#type').val('');
        $('#is_active').val('');
        table.draw();
    });

    $(document).on('click', '.view-tutorial', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('tutorial.show', '') }}/" + id,
            type: 'GET',
            success: function(response) {
                var tutorial = response.data;
                var contentHtml = '';
                
                if (tutorial.type === 'video') {
                    contentHtml = `
                        <div class="embed-responsive embed-responsive-16by9 mb-3">
                            <video class="embed-responsive-item" controls>
                                <source src="${tutorial.url}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    `;
                } else if (tutorial.type === 'image') {
                    contentHtml = `
                        <div class="text-center mb-3">
                            <img src="${tutorial.url}" alt="${tutorial.title}" class="img-fluid" style="max-height: 400px;">
                        </div>
                    `;
                } else if (tutorial.type === 'document') {
                    contentHtml = `
                        <div class="text-center mb-3">
                            <a href="${tutorial.url}" target="_blank" class="btn btn-primary">
                                <i class="fe fe-download"></i> Download Document
                            </a>
                        </div>
                    `;
                }

                var html = `
                    <div class="tutorial-details">
                        <h4>${tutorial.title}</h4>
                        <p><strong>Type:</strong> ${tutorial.type}</p>
                        <p><strong>Status:</strong> ${tutorial.is_active ? 'Active' : 'Inactive'}</p>
                        <p><strong>Expiry Date:</strong> ${tutorial.expiry_date || 'N/A'}</p>
                        <p><strong>Description:</strong></p>
                        <p>${tutorial.description || 'N/A'}</p>
                        ${contentHtml}
                    </div>
                `;
                $('#tutorial-details').html(html);
                $('#viewTutorialModal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-tutorial', function() {
        var id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this tutorial?')) {
            $.ajax({
                url: "{{ route('tutorial.destroy', '') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    table.draw();
                    alert('Tutorial deleted successfully');
                }
            });
        }
    });

    $(document).on('click', '.edit-tutorial', function() {
        var id = $(this).data('id');
        window.location.href = "{{ route('tutorial.edit', '') }}/" + id;
    });
});
</script>
@endpush 