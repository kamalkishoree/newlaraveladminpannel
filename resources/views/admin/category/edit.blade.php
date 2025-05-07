@extends('admin.layouts.master')

@section('page_title')
    {{ __('Update Category') }}
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
    <form action="{{ route('category.update',$category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Update Categories')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('category.index') }}">{{ __('Categories') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('category.update',$category->id) }}">{{ __('Update Categories') }}</a></li>
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
                  <div class="col-md-6">
                                
                           <div class="input-group mb-5">
                            <img src="{{$category->image_url}}" alt="{{$category->slug}}" width="100" height="100">                            
                          </div>
                            <div class="input-group mb-5">
                                <input type="file" id="image1" class="form-control" name="image_url" value="">
                            </div>
                           </div>  <!-- /row end -->


             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                               category's Details
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="required">{{ __('Category Name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control  @error('name') form-control-error @enderror" required="required" value="{{ $category->name }}">

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug" class="required">{{ __('Slug') }}:</label>
                                <input readonly type="text" name="slug" id="slug" class="form-control @error('slug') form-control-error @enderror"  required="required" value="{{ $category->slug  }}">

                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                            <x-ckeditor name="description" id="description" label="Description" :value="$category->description" />
                            @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                    


                        <div class="form-group">
                                <label for="is_feature">{{ __('Feature') }}:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_feature" id="is_feature" data-id="{{$category->id}}" class=" update_category custom-control-input" {{ $category->is_feature ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_feature">
                                    </label>
                                </div>
                            </div>

                    <!-- Toggle for 'is_new' -->
                    <div class="form-group">
                        <label for="is_new">{{ __('New') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_new" id="is_new" data-id="{{$category->id}}" class="update_category custom-control-input" {{ $category->is_new ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_new">
                            </label>
                        </div>
                    </div>

                    <!-- Toggle for 'is_top' -->
                    <div class="form-group">
                        <label for="is_top">{{ __('Top') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_top" id="is_top" data-id="{{$category->id}}" class="update_category custom-control-input" {{ $category->is_top ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_top">
                            </label>
                        </div>
                    </div>


                    <!-- Toggle for 'top_home' -->
                    <div class="form-group">
                        <label for="top_home">{{ __('Top Home Category') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="top_home" id="top_home" data-id="{{$category->id}}" class="update_category custom-control-input" {{ $category->top_home ? 'checked' : '' }}>
                            <label class="custom-control-label" for="top_home">
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
            changeCategoryDetails(id,field,value)

        });

        $('#is_new').change(function() {
            id = $(this).attr('data-id');
            var isNew = $(this).prop('checked')   ? 1 : 0;
            console.log("Is New: " + isNew);
            field = $(this).attr('name');
            value = isNew;
            changeCategoryDetails(id,field,value)

        });
        $('#is_top').change(function() {
            id = $(this).attr('data-id');
            var isTop = $(this).prop('checked')  ? 1 : 0;
            console.log("Is Top: " + isTop);
            field = $(this).attr('name');
            value = isTop;
            changeCategoryDetails(id,field,value)

        });

        $('#top_home').change(function() {
            id = $(this).attr('data-id');
            var top_home = $(this).prop('checked')  ? 1 : 0;
            console.log("Is Top Home: " + top_home);
            field = $(this).attr('name');
            value = top_home;
            changeCategoryDetails(id,field,value)

        });

 });
       


 function changeCategoryDetails(id,field,value) {

            var id = id;
            var field = field;
            var value = value;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `{{ route('category.status_update_custom') }}`,
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
         

   $('#name').keyup(function(){
       let slug = genrateSlug($(this).val());
       $('#slug').val(slug);
    });


</script>
@endpush
