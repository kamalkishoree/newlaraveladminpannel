@extends('admin.layouts.master')

@section('page_title')
    {{ __('Create Tutorial') }}
@endsection

@section('content')
    <form action="{{ route('tutorial.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Create Tutorial')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tutorial.index') }}">{{ __('Tutorials') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a href="{{ route('tutorial.create') }}">{{ __('Add New Tutorial') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <button type="submit" class="btn custom-create-btn">{{ __('default.form.save-button') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            Tutorial Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title" class="required">{{ __('Title') }}:</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') form-control-error @enderror" required value="{{ old('title') }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">{{ __('Description') }}:</label>
                            <textarea name="description" id="description" class="form-control @error('description') form-control-error @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="url" class="required">{{ __('URL') }}:</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') form-control-error @enderror" required value="{{ old('url') }}">
                            @error('url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type" class="required">{{ __('Type') }}:</label>
                            <select name="type" id="type" class="form-control @error('type') form-control-error @enderror" required>
                                <option value="">Select Type</option>
                                <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                                <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="thumbnail-field" style="display: none;">
                            <label for="thumbnail">{{ __('Thumbnail') }}:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('thumbnail') form-control-error @enderror" id="thumbnail" name="thumbnail">
                                <label class="custom-file-label" for="thumbnail">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Only required for video type. Recommended size: 1280x720px</small>
                            @error('thumbnail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">{{ __('Expiry Date') }}:</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control @error('expiry_date') form-control-error @enderror" value="{{ old('expiry_date') }}">
                            @error('expiry_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="is_active">{{ __('Status') }}:</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Show/hide thumbnail field based on type selection
        $('#type').change(function() {
            if ($(this).val() === 'video') {
                $('#thumbnail-field').show();
            } else {
                $('#thumbnail-field').hide();
            }
        });

        // Trigger change event on page load if type is already selected
        if ($('#type').val() === 'video') {
            $('#thumbnail-field').show();
        }

        // Update file input label with selected file name
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endpush 