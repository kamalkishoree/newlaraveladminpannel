@extends('admin.layouts.master')

@section('page_title')
    {{ __('Brand Create') }}
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
    <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Create Brand')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('brand.index') }}">{{ __('Brands') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('brand.create') }}">{{ __('Add new Brands') }}</a></li>
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
                                <input type="file" id="image1" class="form-control" name="image_url">
                            </div>
                           </div>  <!-- /row end -->


             <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                               Brand's Details
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="required">{{ __('Brand Name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') form-control-error @enderror" required="required" value="{{ old('name') }}">

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug" class="required">{{ __('Slug') }}:</label>
                                <input readonly type="text" name="slug" id="slug" class="form-control @error('slug') form-control-error @enderror"  required="required" value="{{ old('slug') }}">

                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="target_url" class="required">{{ __('Target URL') }}:</label>
                                <input type="text" name="target_url" id="target_url" class="form-control @error('target_url') form-control-error @enderror"  required="required" value="{{ old('target_url') }}">
                                @error('target_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Cloaking URL Domain --}}
                            <div class="form-group d-none">
                            <label for="clocking_url" class="required">{{ __('Clocking URL') }}:</label>
                                    <input  disabled  type="text" name="clocking_url" id="clocking_url" class="form-control @error('clocking_url') form-control-error @enderror"  required="required" value="{{ old('clocking_url') }}">
                                    @error('clocking_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>

                            {{-- Profit Tracking Time --}}
                        <div class="form-group">
                            <label for="profit_tracking_time">{{ __('Profit Tracking Time') }} (Hours):</label>
                            <input type="text" name="profit_tracking_hours" id="profit_tracking_hours" class="form-control" value="{{ old('profit_tracking_hours') }}">
                        </div>

                        {{-- Profit Confirmation Time --}}
                        <div class="form-group">
                            <label for="profit_confirmation_time">{{ __('Profit Confirmation Time') }} (Days):</label>
                            <input type="text" name="profit_confirmation_days" id="profit_confirmation_days" class="form-control" value="{{ old('profit_confirmation_days') }}">
                        </div>

                    {{-- Cashback Rates 
                    <div class="form-group">
                        <label>{{ __('Cashback Rates') }}:</label>
                        <div class="input-group">
                            <input type="text" name="cashback_profit" class="form-control" placeholder="Profit" value="{{ old('cashback_profit') }}">
                        </div>
                    </div>--}}


                    <div class="form-group cashback-rates-main">
                        <label>{{ __('Cashback Rates') }} : </label> <button style="" type="button" class="add_more_cashback_rates btn custom-create-btn m-2">ADD <i class="fa-solid fa-plus"></i></button>
                    </div>


                    {{-- Cashback Terms --}}
                    <div class="form-group">
                        <x-ckeditor name="cashback_terms" id="cashback_terms" label="Cashback Terms"  />
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
                        <input type="text" name="payout_amount" class="form-control" placeholder="Enter Amount ..." value="{{ old('payout_amount') }}">
                      </div>
                     </div>
                        {{-- Affiliate Network Dropdown --}}
                        <div class="form-group">
                            <label for="affiliate_network">{{ __('Choose Affiliate Network') }}:</label>
                            <select name="affiliate_network_id" id="affiliate_network_id" class="form-control">
                                <option value="">Select Network</option>
                                @foreach($affiliate_partners as $network)
                                    <option value="{{ $network->id }}" {{ old('affiliate_network_id') == $network->id ? 'selected' : '' }}>
                                        {{ $network->provider_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                            <div class="form-group">
                                <x-ckeditor name="description" id="description" label="Description"  />
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="category_id" class="required">{{ __('Category') }}:</label>
                                <select  name="category_id" id="category_id" class="form-control @error('category_id') form-control-error @enderror"  required="required" value="{{ old('category_id') }}">
                                <option value="">select category ...</option>
                                @foreach($categories as $category)   
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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

<script>
	// document.addEventListener("DOMContentLoaded", function() {
	// 	document.getElementById('button-image').addEventListener('click', (event) => {

	// 		event.preventDefault();
	// 		inputId = 'image1';
	// 		window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');

	// 	});
	// });

	// input
	// let inputId = '';
	// let output = 'output';

	// // set file link
	// function fmSetLink($url) {
	// 	document.getElementById(inputId).value = $url;
	// 	document.getElementById(output).src = $url;
	// }
</script>


<script>
    let cashbackIndex = 0;
    $('.add_more_cashback_rates').on('click', function () {
        let newRow = `
            <div class="row cashback-rate-row mb-2">
                <div class="input-group col-md-3">
                    <input type="text" name="cashback_rate[${cashbackIndex}][profit]" class="form-control" placeholder="Enter Value">
                </div>
                <div class="input-group col-md-8">
                    <input type="text" name="cashback_rate[${cashbackIndex}][description]" class="form-control" placeholder=" Enter Description">
                </div>
                <div class="col-md-1">
                 <button type="button" class="btn btn-danger btn-sm remove-cashback-rate">
                    <i class="fa fa-trash"></i>
                 </button>
               </div>
            </div>
        `;
        $('.cashback-rates-main').append(newRow);
        cashbackIndex++;
    });


// Delete row on trash icon click
$(document).on('click', '.remove-cashback-rate', function () {
    $(this).closest('.cashback-rate-row').remove();
});


$('#name').keyup(function(){
       let slug = genrateSlug($(this).val());
       $('#slug').val(slug);
    });
</script>

@endpush
