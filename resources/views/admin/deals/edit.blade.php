@extends('admin.layouts.master')

@section('page_title')
    {{ __('Update Deal') }}
@endsection

@push('css')
    <style>
        #output {
            height: 300px;
            width: 300px;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('deal.update',$deal->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('UPDATE DEAL')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('deal.index') }}">{{ __('Deals') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('deal.create') }}">{{ __('Add new Deals') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <div class="create-btn pull-right">
                            <button type="submit"
                                class="btn custom-create-btn">{{ __('default.form.save-button') }}</button>
                        </div>
                    </div>
                </div>
            </div><!-- /card finish -->	
        </div><!-- /Page Header -->
            <div class="row">
                {{--  <div class="col-md-6">
                            <div class="input-group mb-5">
                                <input type="file" id="image1" class="form-control" name="image_url">
                            </div>
                  </div>  <!-- /row end -->
                  --}}

             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                               Deal Form
                            </h5>
                        </div>

                    
                          <div class="card-body">
                            <div class="form-group">
                                <label for="headline" class="required">{{ __('HEADLINE') }}:</label>
                                <input type="text" name="headline" id="headline" class="form-control @error('headline') form-control-error @enderror" required="required" value="{{ $deal->headline }}">

                                @error('headline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sub_headline" class="required">{{ __('SUB HEADLINE') }}:</label>
                                <input type="text" name="sub_headline" id="sub_headline" class="form-control @error('sub_headline') form-control-error @enderror" required="required" value="{{ $deal->sub_headline }}">

                                @error('sub_headline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="code" class="required">{{ __('CODE') }}:</label>
                                <input type="text" name="code" id="code" class="form-control @error('code') form-control-error @enderror"  required="required" value="{{ $deal->code }}"/>
                                @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="target_url" class="required">{{ __('TARGET URL') }}:</label>
                                <input type="text" name="target_url" id="target_url" class="form-control @error('target_url') form-control-error @enderror"  required="required" value="{{ $deal->target_url }}">
                                @error('target_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="form-group">
                                <label for="clocking_url" class="required">{{ __('CLOCKING URL') }}:</label>
                                <input type="text" name="clocking_url" id="clocking_url" class="form-control @error('clocking_url') form-control-error @enderror"  required="required" value="{{$deal->clocking_url }}">
                                @error('clocking_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                            <x-ckeditor name="description" id="description" label="OTHER DETAILS"  :value="$deal->description" />
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sharing_message" class="required">{{ __('SHARING MESSAGE') }}:</label>
                                <input type="text" name="sharing_message" id="message" class="form-control @error('sharing_message') form-control-error @enderror"  required="required" value="{{ $deal->sharing_message }}"/>
                                @error('sharing_message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="brand_id" class="required">{{ __("DEAL's BRAND") }}:</label>
                                <select  name="brand_id" id="brand_id" class="form-control @error('brand_id') form-control-error @enderror"  required="required">
                                <option value="">select brands ...</option>
                                @foreach($brands as $brand)   
                                <option value="{{$brand->id}}" {{($deal->brand_id == $brand->id)?'selected':''}}>{{$brand->name}}</option>
                                @endforeach
                                </select>
                                @error('brand_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="expires_at" class="required">{{ __('EXPIRE ON') }}:</label>
                                <input type="datetime-local" name="expires_at" id="message" class="form-control @error('expires_at') form-control-error @enderror"  required="required" value="{{ $deal->expires_at }}"/>
                                @error('expires_at')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                    <div class="form-group">
                            <label for="is_feature">{{ __('Feature') }}:</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_feature" id="is_feature" data-id="{{$deal->id}}" class=" update_deal custom-control-input" {{ $deal->is_feature ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_feature">
                                </label>
                            </div>
                        </div>

                    <!-- Toggle for 'is_new' -->
                    <div class="form-group">
                        <label for="is_new">{{ __('New') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_new" id="is_new" data-id="{{$deal->id}}" class="update_deal custom-control-input" {{ $deal->is_new ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_new">
                            </label>
                        </div>
                    </div>

                    <!-- Toggle for 'is_top' -->
                    <div class="form-group">
                        <label for="is_top">{{ __('Top') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_top" id="is_top" data-id="{{$deal->id}}" class="update_deal custom-control-input" {{ $deal->is_top ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_top">
                            </label>
                        </div>
                    </div>

                        </div> <!-- card-body-end -->
                    </div> <!-- card-end -->
                </div> <!-- col-md-4-end -->

            </div> <!-- row-end -->

        </div> <!-- card-body-end -->

    </form>
@endsection


@push('scripts')
<script>
	var loadFileImageFront = function(event) {
		var output = document.getElementById('output');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>

<script type="text/javascript">

$(document).ready(function() {
    let field = '';
    let value = '';
    let id ='';
        $('#is_feature').change(function() {
            id = $(this).attr('data-id');
            var is_feature = $(this).prop('checked') ? 1 : 0;
            console.log("Is feature: " + is_feature);
            field = $(this).attr('name');
            value = is_feature;
            changedealDetails(id,field,value)

        });

        $('#is_new').change(function() {
            id = $(this).attr('data-id');
            var isNew = $(this).prop('checked')   ? 1 : 0;
            console.log("Is New: " + isNew);
            field = $(this).attr('name');
            value = isNew;
            changedealDetails(id,field,value)

        });
        $('#is_top').change(function() {
            id = $(this).attr('data-id');
            var isTop = $(this).prop('checked')  ? 1 : 0;
            console.log("Is Top: " + isTop);
            field = $(this).attr('name');
            value = isTop;
            changedealDetails(id,field,value)

        });

 });
       


 function changedealDetails(id,field,value) {

            var id = id;
            var field = field;
            var value = value;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `{{ route('deal.status_update_custom') }}`,
                type: 'GET',
                data: {
                    _token: _token,
                    id:id,
                    field: field,
                    value: value
                },
                success: function(result) {
					if(value == 1){
                    	toastr.success(result.message);
                	}else{
                    	toastr.error(result.message);
                	} 
                }
            });
        }


</script>
@endpush
