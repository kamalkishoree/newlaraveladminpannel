@extends('admin.layouts.master')

@section('page_title')
{{__('dashboard.title')}}
@endsection

@push('css')
<style>

</style>
@endpush

@section('content')


<!-- Page Header -->
<div class="page-header">
    <div class="card breadcrumb-card">
        <div class="row justify-content-between align-content-between" style="height: 100%;">
            <div class="col-md-6">
                <h3 class="page-title">Kenonn Rawat</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="create-btn pull-right d-flex align-items-center">
                    <!-- <a href="{{ route('users.create') }}" class="btn custom-create-btn">Export2</a> -->
                    <div class="dropdown">
                        <button type="button" class="btn custom-create-btn btn_days dropdown-toggle"
                            data-toggle="dropdown">
                            <i class="fa-solid fa-calendar-days mr-1"></i> Last 7 Days
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Last 7 Days</a>
                            <a class="dropdown-item" href="#">Last 1 Week</a>
                            <a class="dropdown-item" href="#">Last 1 Month</a>
                        </div>
                    </div>
                    <a href="{{ route('users.create') }}" class="btn custom-create-btn ml-2 btn_days"> <i
                            class="fa fa-share mr-2" aria-hidden="true"></i> Export</a>
                </div>
            </div>
        </div>
    </div><!-- /card finish -->
</div><!-- /Page Header -->


<div class="row">
    <div class="col-xl-3 d-flex">
        <div class="card w-100">
            <div class="card-body card_channel">
                <h5>Session By Channel</h5>
                <h6>Empoly Name This Month</h6>
                <ul class="nav d-block list_channeels">
                    <li>
                        Cannor Chandlar <span>₹2000</span>
                    </li>
                    <li>
                        Russel Floyd <span>₹854</span>
                    </li>
                    <li>
                        Cannor Chandlar <span>₹2000</span>
                    </li>
                    <li>
                        Russel Floyd <span>₹854</span>
                    </li>
                    <li>
                        Cannor Chandlar <span>₹2000</span>
                    </li>
                    <li>
                        Russel Floyd <span>₹854</span>
                    </li>
                    <li>
                        Cannor Chandlar <span>₹2000</span>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="row">
            <div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon text-primary border-primary">
                                <i class="fe fe-users"></i>
                            </span>
                            <div class="dash-count">
                                <h3>15</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Users</h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary w-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon text-success">
                                <i class="fe fe-credit-card"></i>
                            </span>
                            <div class="dash-count">
                                <h3>10</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">

                            <h6 class="text-muted">Admin</h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success w-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon text-danger border-danger">
                                <i class="fe fe-money"></i>
                            </span>
                            <div class="dash-count">
                                <h3>475</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">

                            <h6 class="text-muted">Appointment</h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-danger w-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon text-warning border-warning">
                                <i class="fe fe-folder"></i>
                            </span>
                            <div class="dash-count">
                                <h3>$6523</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">

                            <h6 class="text-muted">Revenue</h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning w-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 d-flex">
        <div class="card w-100">
            <div class="card-body card_channel">
                <h5>Card Title</h5>
                <h6 class="mb-2">Total Earning</h6>
                <h3>₹283497</h3>
                <h4 class="last_month">1.4% Since Last Month</h4>
                <hr class="my-2">
                <h6 class="mb-2">Total Earning</h6>
                <h3>₹87492</h3>
                <h4 class="last_month">5.4% Since Last Month</h4>
            </div>

        </div>
    </div>

</div>


<div class="row mt-2">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div id="morrisArea"></div>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div id="morrisLine"></div>

            </div>
        </div>
    </div>
</div>



@endsection




@push('css')

{{-- <link rel="stylesheet" href="{{ asset('assets/css/c3.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/css/chartist.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/jquery-jvectormap-2.0.2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/chartist.min.css') }}" /> --}}
<style type="text/css">
.card {
    background-color: #fff;
}
</style>

@endpush

@push('scripts')

@endpush