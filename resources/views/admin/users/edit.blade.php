@extends('admin.layouts.master')

@section('page_title')
    {{__('user.edit.title')}}
@endsection

@push('css')
	<style>
		#output{
			height: 300px;
			width: 300px;
		}

		.user-profile-header {
			background: #fff;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.profile-image-container {
			width: 150px;
			height: 150px;
			margin: 0 auto 20px;
			position: relative;
		}

		.profile-image {
			width: 100%;
			height: 100%;
			border-radius: 50%;
			object-fit: cover;
			border: 4px solid #fff;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.user-info {
			text-align: center;
		}

		.user-name {
			font-size: 24px;
			font-weight: 600;
			margin-bottom: 5px;
		}

		.user-email {
			color: #666;
			margin-bottom: 15px;
		}

		.stats-card {
			background: #fff;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			transition: all 0.3s ease;
		}

		.stats-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
		}

		.stats-icon {
			width: 40px;
			height: 40px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 50%;
			font-size: 20px;
			margin-bottom: 15px;
		}

		.stats-value {
			font-size: 24px;
			font-weight: 600;
			margin-bottom: 5px;
		}

		.stats-label {
			color: #666;
			font-size: 14px;
		}

		.section-title {
			font-size: 18px;
			font-weight: 600;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 2px solid #f0f0f0;
		}

		.form-section {
			background: #fff;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
		}

		.form-section .section-title {
			font-size: 16px;
			font-weight: 600;
			margin-bottom: 20px;
			color: #333;
		}

		.form-group {
			margin-bottom: 1.5rem;
		}

		.form-group label {
			font-weight: 500;
			margin-bottom: 0.5rem;
			color: #444;
		}

		.form-group label.required:after {
			content: " *";
			color: #dc3545;
		}

		.input-group-text {
			background-color: #f8f9fa;
			border-right: none;
		}

		.input-group .form-control {
			border-left: none;
		}

		.input-group .form-control:focus {
			border-color: #ced4da;
			box-shadow: none;
		}

		.input-group .form-control:focus + .input-group-text {
			border-color: #ced4da;
		}

		.custom-file-label {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.select2-container--default .select2-selection--multiple {
			border-color: #ced4da;
		}

		.select2-container--default.select2-container--focus .select2-selection--multiple {
			border-color: #80bdff;
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
		}

		.btn-primary {
			padding: 0.5rem 1.5rem;
			font-weight: 500;
		}

		.text-muted {
			font-size: 0.875rem;
		}
	</style>
@endpush

@section('content')
	<form method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
		@csrf()

		<div class="page-header">
			<div class="card breadcrumb-card">
				<div class="row justify-content-between align-content-between" style="height: 100%;">
					<div class="col-md-6">
						<h3 class="page-title">{{__('user.index.title')}}</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
							</li>
							<li class="breadcrumb-item">
								<a href="{{ route('users.index') }}">{{ __('user.index.title') }}</a>
							</li>
							<li class="breadcrumb-item active-breadcrumb">
								<a href="{{ route('users.edit', $user->id) }}">{{ __('user.edit.title') }} - ({{ $user->name }})</a>
							</li>
						</ul>
					</div>
					<div class="col-md-3">
						<div class="create-btn pull-right">
							<button type="submit" class="btn custom-create-btn">{{ __('default.form.update-button') }}</button>
						</div>
					</div>
				</div>
			</div><!-- /card finish -->	
		</div><!-- /Page Header -->

		<div class="card-body">

			<div class="row">
				<div class="col-md-12">
					<div class="user-profile-header">
						<div class="profile-image-container">
							@if($user->image)
								<img src="{{ $user->image }}" alt="User Profile" class="profile-image">
							@else
								<img src="/assets/admin/img/default-user.png" alt="Default Profile" class="profile-image">
							@endif
						</div>
						<div class="user-info">
							<h2 class="user-name">{{ $user->name }}</h2>
							<p class="user-email">{{ $user->email }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<h4 class="section-title">User Activity Summary</h4>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('referral.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-info text-white">
								<i class="fas fa-users"></i>
							</div>
							<div class="stats-value">{{ $myreferral ? $myreferral->count() : 0 }}</div>
							<div class="stats-label">Total Referrals</div>
						</div>
					</a>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('campaign.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-success text-white">
								<i class="fas fa-mouse-pointer"></i>
							</div>
							<div class="stats-value">{{ $user_clicks ? $user_clicks->count() : 0 }}</div>
							<div class="stats-label">Total Clicks</div>
						</div>
					</a>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('conversion.view') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-warning text-white">
								<i class="fas fa-exchange-alt"></i>
							</div>
							<div class="stats-value">{{ $user_conversion ? $user_conversion->count() : 0 }}</div>
							<div class="stats-label">Approved Conversions</div>
						</div>
					</a>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('ticket.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-primary text-white">
								<i class="fas fa-ticket-alt"></i>
							</div>
							<div class="stats-value">{{ $user_ticket ? $user_ticket->count() : 0 }}</div>
							<div class="stats-label">Total Tickets</div>
						</div>
					</a>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-md-12">
					<h4 class="section-title">Withdrawal Summary</h4>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('withdrawal.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-warning text-white">
								<i class="fas fa-clock"></i>
							</div>
							<div class="stats-value">Rs. {{ number_format($user_withdrawls_pending, 2) }}</div>
							<div class="stats-label">Pending Withdrawals</div>
						</div>
					</a>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('withdrawal.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-success text-white">
								<i class="fas fa-check-circle"></i>
							</div>
							<div class="stats-value">Rs. {{ number_format($user_withdrawls_approved, 2) }}</div>
							<div class="stats-label">Approved Withdrawals</div>
						</div>
					</a>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('withdrawal.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-danger text-white">
								<i class="fas fa-times-circle"></i>
							</div>
							<div class="stats-value">Rs. {{ number_format($user_withdrawls_rejected, 2) }}</div>
							<div class="stats-label">Rejected Withdrawals</div>
						</div>
					</a>
				</div>

				<div class="col-xl-3 col-sm-6">
					<a href="{{ route('withdrawal.index') }}" class="text-decoration-none">
						<div class="stats-card">
							<div class="stats-icon bg-info text-white">
								<i class="fas fa-history"></i>
							</div>
							<div class="stats-value">
								@if($recent_withdrawl)
									{{ $recent_withdrawl->created_at->diffForHumans() }}
								@else
									No withdrawals
								@endif
							</div>
							<div class="stats-label">Last Withdrawal</div>
						</div>
					</a>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title mb-0">Edit User Information</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-section">
										<h5 class="section-title">Personal Information</h5>
										<div class="form-group">
											<label for="name" class="required">{{__('default.form.name')}}</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">
														<i class="fas fa-user"></i>
													</span>
												</div>
												<input type="text" name="name" id="name" 
													class="form-control @error('name') form-control-error @enderror" 
													required="required" value="{{$user->name}}"
													placeholder="Enter full name">
											</div>
											@error('name')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="form-group">
											<label for="mobile">{{__("default.form.mobile")}}</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">
														<i class="fas fa-phone"></i>
													</span>
												</div>
												<input type="number" name="mobile" id="mobile" 
													class="form-control @error('mobile') form-control-error @enderror" 
													disabled value="{{$user->mobile}}"
													placeholder="Enter mobile number">
											</div>
											@error('mobile')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="form-group">
											<label for="image">Profile Image</label>
											<div class="custom-file">
												<input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
												<label class="custom-file-label" for="image">Choose file</label>
											</div>
											<small class="form-text text-muted">Recommended size: 300x300 pixels</small>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-section">
										<h5 class="section-title">Authentication & Role</h5>
										<div class="form-group">
											<label for="email">{{__("default.form.email")}}</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">
														<i class="fas fa-envelope"></i>
													</span>
												</div>
												<input type="email" name="email" id="email" 
													class="form-control @error('email') form-control-error @enderror" 
													value="{{$user->email}}" disabled
													placeholder="Enter email address">
											</div>
											@error('email')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="form-group">
											<label for="password">{{__("default.form.password")}}</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">
														<i class="fas fa-lock"></i>
													</span>
												</div>
												<input type="password" name="password" id="password" 
													class="form-control @error('password') form-control-error @enderror"
													placeholder="Enter new password">
											</div>
											<small class="form-text text-muted">Leave blank to keep current password</small>
											@error('password')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="form-group">
											<label for="password-confirm">{{__("default.form.password-confirm")}}</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">
														<i class="fas fa-lock"></i>
													</span>
												</div>
												<input type="password" name="confirm-password" id="password-confirm" 
													class="form-control @error('password-confirm') form-control-error @enderror"
													placeholder="Confirm new password">
											</div>
											@error('confirm-password')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="form-group">
											<label for="roles" class="required">{{ __('default.form.role') }}</label>
											<select name="roles[]" id="roles" class="select2" multiple="multiple">
												@foreach ($roles as $role)
													<option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
														{{ ucfirst($role->name) }}
													</option>
												@endforeach
											</select>
											@error('roles')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>
									</div>
								</div>
							</div>

							<div class="row mt-4">
								<div class="col-12 text-right">
									<button type="submit" class="btn btn-quicks">
										<i class="fas fa-save mr-1"></i> {{ __('default.form.update-button') }}
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	document.addEventListener("DOMContentLoaded", function() {
	document.getElementById('button-image').addEventListener('click', (event) => {
		event.preventDefault();
		inputId = 'image1';
		window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
		});
	});

	// input
	let inputId = '';
	let output = 'output';

	// set file link
	function fmSetLink($url) {
	document.getElementById(inputId).value = $url;
	document.getElementById(output).src = $url;
	}
</script>

<script>
	// Update custom file input label
	document.querySelector('.custom-file-input').addEventListener('change', function(e) {
		var fileName = e.target.files[0].name;
		var nextSibling = e.target.nextElementSibling;
		nextSibling.innerText = fileName;
	});

	// Initialize select2
	$(document).ready(function() {
		$('.select2').select2({
			theme: 'bootstrap4',
			width: '100%'
		});
	});
</script>
@endpush