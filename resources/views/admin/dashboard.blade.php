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
                {{--<h3 class="page-title">{{Auth::user()->name}}</h3>--}}
                <h3 class="page-title">{{'DASHBOARD'}}</h3>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item active-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>
            </div>
            {{--   <div class="col-md-6">
                <div class="create-btn pull-right d-flex align-items-center">
                    <!-- <a href="{{ route('users.create') }}" class="btn custom-create-btn">Export2</a> -->
               <div class="dropdown">
                        <button type="button" class="btn custom-create-btn btn_days dropdown-toggle"
                            data-toggle="dropdown">
                            <i class="fa-solid fa-calendar-days mr-1"></i> Last 7 Days
                        </button>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('dashboard',[7])}}">Last 7 Days</a>
                

                        <a class="dropdown-item" href="#">Last 7 Days</a>
                            <a class="dropdown-item" href="#">Last 1 Week</a>
                            <a class="dropdown-item" href="#">Last 1 Month</a>
                        </div>
                    </div>
                    <a href="{{ route('users.create') }}" class="btn custom-create-btn ml-2 btn_days"> <i
                            class="fa fa-share mr-2" aria-hidden="true"></i> Export</a>
                </div>--}}
            </div>
        </div>
    </div><!-- /card finish -->
</div><!-- /Page Header -->


<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon text-primary border-primary">
                                <i class="fe fe-users"></i>
                            </span>
                            <div class="dash-count">
                                <h3>{{$user->count()}}</h3>
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
                                <h3>{{$campaign_all->count()}}</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">

                            <h6 class="text-muted">Clicks   </h6>
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

                            <h6 class="text-muted">Conversions</h6>
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
</div>



<div class="row mt-2">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
            
                <x-highchart 
                    chart-id="chart1"
                    chart-type="pie"
                    title="User Data" 
                    subtitle=""
                    :categories="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                    :series="$series"
                />

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
               <x-high-chart
                    chart-id="chawwrt1"
                    chart-type="bar"
                    title="Clicks Data" 
                    subtitle=""
                    :categories="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                    :series="array_values($campaign)"
                />
                </div>
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



