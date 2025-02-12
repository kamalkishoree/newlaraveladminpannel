@props(['name', 'id', 'options' => [], 'selected' => null, 'multiple' => false, 'placeholder' => 'Select an option'])

<select name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $id }}" class="form-control select2" {{ $multiple ? 'multiple' : '' }}>
    <option value="all">{{ 'All' }}</option>
    @foreach ($options as $key => $value)
        <option value="{{ $value->id }}" {{ ($selected && in_array($key, (array) $selected)) ? 'selected' : '' }}>
            {{ $value->name }}
        </option>
    @endforeach
</select>

@push('scripts')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#{{ $id }}').select2({
                placeholder: "{{ $placeholder }}",  
                allowClear: true
            });
        });
    </script>
@endpush