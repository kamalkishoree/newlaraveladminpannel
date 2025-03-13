@extends('admin.layouts.master')

@section('page_title')
    {{ __('Wallet Create') }}
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
    <form action="{{ route('wallet.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <div class="card breadcrumb-card">
                <div class="row justify-content-between align-content-between" style="height: 100%;">
                    <div class="col-md-6">
                        <h3 class="page-title">{{__('Create Wallet')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('wallet.index') }}">{{ __('Wallets') }}</a></li>
                            <li class="breadcrumb-item active-breadcrumb"><a
                                    href="{{ route('wallet.create') }}">{{ __('Add new Wallets') }}</a></li>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Wallet Details</h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="balance" class="required">{{ __('Balance') }}:</label>
                                <input type="number" name="balance" id="balance" class="form-control @error('balance') form-control-error @enderror" required value="{{ old('balance', 0.00) }}" step="0.01">
                                @error('balance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="pending_balance">{{ __('Pending Balance') }}:</label>
                                <input type="number" name="pending_balance" id="pending_balance" class="form-control" value="{{ old('pending_balance', 0.00) }}" step="0.01">
                            </div>

                            <div class="form-group">
                                <label for="withdrawn_balance">{{ __('Withdrawn Balance') }}:</label>
                                <input type="number" name="withdrawn_balance" id="withdrawn_balance" class="form-control" value="{{ old('withdrawn_balance', 0.00) }}" step="0.01">
                            </div>

                            <div class="form-group">
                                <label for="currency" class="required">{{ __('Currency') }}:</label>
                                <input type="text" name="currency" id="currency" class="form-control @error('currency') form-control-error @enderror" required value="{{ old('currency', 'USD') }}">
                                @error('currency')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="wallet_type" class="required">{{ __('Wallet Type') }}:</label>
                                <input type="text" name="wallet_type" id="wallet_type" class="form-control @error('wallet_type') form-control-error @enderror" required value="{{ old('wallet_type', 'default') }}">
                                @error('wallet_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_active">{{ __('Status') }}:</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
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


@endpush
