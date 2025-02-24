@extends('admin.layouts.master')

@section('page_title')
    {{ __('Update Brand') }}
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
    <form action="{{ route('brand.update',$brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Update Brand')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('brand.index') }}">{{ __('Brand') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('brand.update',$brand->id) }}">{{ __('Update Brand') }}</a></li>
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
                            <img src="{{$brand->image_url}}" alt="{{$brand->slug}}" width="100" height="100">                            
                          </div>
                            <div class="input-group mb-5">
                                <input type="file" id="image1" class="form-control" name="image_url" value="">
                            </div>
                           </div>  <!-- /row end -->

             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                               brand's Details
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="required">{{ __('Brand Name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control  @error('name') form-control-error @enderror" required="required" value="{{ $brand->name }}">

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug" class="required">{{ __('Slug') }}:</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') form-control-error @enderror"  required="required" value="{{ $brand->slug  }}">

                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="target_url" class="required">{{ __('Target URL') }}:</label>
                                <input type="text" name="target_url" id="target_url" class="form-control @error('target_url') form-control-error @enderror"  required="required" value="{{ $brand->target_url }}">
                                @error('target_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        {{-- Cloaking URL Domain --}}
                            <div class="form-group">
                            <label for="clocking_url" class="required">{{ __('Clocking URL') }}:</label>
                                    <input type="text" name="clocking_url" id="clocking_url" class="form-control @error('clocking_url') form-control-error @enderror"  required="required" value="{{ $brand->clocking_url }}">
                                    @error('clocking_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>

                            {{-- Profit Tracking Time --}}
                        <div class="form-group">
                            <label for="profit_confirmation_hours">{{ __('Profit Tracking Time') }} (Hours):</label>
                            <input type="text" name="profit_tracking_hours" id="profit_tracking_hours" class="form-control" value="{{ $brand->profit_tracking_hours }}">
                        </div>

                        {{-- Profit Confirmation Time --}}
                        <div class="form-group">
                            <label for="profit_confirmation_days">{{ __('Profit Confirmation Time') }} (Days):</label>
                            <input type="text" name="profit_confirmation_days" id="profit_confirmation_days" class="form-control" value="{{ $brand->profit_confirmation_days }}">
                        </div>

                    {{-- Cashback Rates --}}
                    <div class="form-group">
                        <label>{{ __('Cashback Rates') }}:</label>
                        <div class="input-group">
                            <input type="text" name="cashback_profit" class="form-control" placeholder="Profit" value="{{ $brand->cashback_profit }}">
                        </div>
                    </div>

                    {{-- Cashback Terms --}}
                    <div class="form-group">
                        <x-ckeditor name="cashback_terms" id="cashback_terms" label="Cashback Terms"  :value="$brand->cashback_terms" />
                    </div>

                  {{-- Payout Type --}}
                  <div class="form-group">
                    <label>{{ __('Payout Type') }}:</label>
                    <div class="input-group mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input payout_type" type="radio" id="flat" name="payout_type" value="flat" checked>
                            <label class="form-check-label" for="flat">Flat</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input payout_type" type="radio" id="percentage" name="payout_type" value="percentage">
                            <label class="form-check-label" for="percentage">Percentage</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input payout_type" type="radio" id="custom" name="payout_type" value="custom">
                            <label class="form-check-label" for="custom">Custom</label>
                        </div>
                        <input type="text" name="payout_amount" class="form-control" placeholder="Enter Amount ..." value="">
                      </div>
                     </div>

                 
                        {{-- Affiliate Network Dropdown --}}
                        <div class="form-group">
                            <label for="affiliate_network">{{ __('Choose Affiliate Network') }}:</label>
                            <select name="affiliate_network_id" id="affiliate_network_id" class="form-control">
                                <option value="">Select Network</option>
                                @foreach($affiliate_partners as $network)
                                    <option value="{{ $network->id }}" {{ $brand->affiliate_network_id == $network->id ? 'selected' : '' }}>
                                        {{ $network->provider_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                            <div class="form-group">
                              <x-ckeditor name="description" id="description" label="Description" :value="$brand->description" />
                              @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category_id" class="required">{{ __('Category') }}:</label>
                                <select  name="category_id" id="category_id" class="form-control @error('category_id') form-control-error @enderror"  required="required" >
                                <option value="">Select Category ...</option>
                                @foreach($categories as $category)   
                                <option value="{{$category->id}}" {{( $category->id == $brand->category_id)?'selected':''}} @endphp>{{$category->name}}</option>
                                @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                    <div class="form-group">
                            <label for="is_feature">{{ __('Feature') }}:</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_feature" id="is_feature" data-id="{{$brand->id}}" class=" update_category custom-control-input" {{ $brand->is_feature ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_feature">
                                </label>
                            </div>
                        </div>

                    <!-- Toggle for 'is_new' -->
                    <div class="form-group">
                        <label for="is_new">{{ __('New') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_new" id="is_new" data-id="{{$brand->id}}" class="update_category custom-control-input" {{ $brand->is_new ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_new">
                            </label>
                        </div>
                    </div>

                    <!-- Toggle for 'is_top' -->
                    <div class="form-group">
                        <label for="is_top">{{ __('Top') }}:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_top" id="is_top" data-id="{{$brand->id}}" class="update_category custom-control-input" {{ $brand->is_top ? 'checked' : '' }}>
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
            changeBrandDetails(id,field,value)

        });

        $('#is_new').change(function() {
            id = $(this).attr('data-id');
            var isNew = $(this).prop('checked')   ? 1 : 0;
            console.log("Is New: " + isNew);
            field = $(this).attr('name');
            value = isNew;
            changeBrandDetails(id,field,value)

        });
        $('#is_top').change(function() {
            id = $(this).attr('data-id');
            var isTop = $(this).prop('checked')  ? 1 : 0;
            console.log("Is Top: " + isTop);
            field = $(this).attr('name');
            value = isTop;
            changeBrandDetails(id,field,value)

        });

 });
       


 function changeBrandDetails(id,field,value) {

            var id = id;
            var field = field;
            var value = value;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `{{ route('brand.status_update_custom') }}`,
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
