@props(['name', 'id', 'label' => 'Content', 'value' => '', 'placeholder' => 'Enter text...'])

<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $id }}" class="form-control ckeditor" placeholder="{{ $placeholder }}">
        {{ old($name, $value) }}
    </textarea>
</div>

@push('scripts')
   

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            ClassicEditor
                .create(document.querySelector("#{{ $id }}"))
                .catch(error => console.error(error));
        });
    </script>

@endpush
