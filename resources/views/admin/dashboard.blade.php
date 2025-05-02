@extends('admin.layouts.master')

@section('page_title')
{{__('dashboard.title')}}
@endsection

@push('css')
<style>
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.date-filter-section {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.stats-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.dash-widget-header {
    padding: 20px;
}

.dash-widget-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 24px;
}

.custom-date-picker {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    padding: 5px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.custom-date-picker input[type="date"] {
    border: 1px solid #ddd;
    padding: 6px 10px;
    border-radius: 4px;
    min-width: 140px;
}

.custom-date-picker .input-group-text {
    background: #f8f9fa;
    border: 1px solid #ddd;
    padding: 6px 12px;
}

.btn_days {
    padding: 8px 15px;
    border-radius: 4px;
    font-weight: 500;
}

.chart-container {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="card breadcrumb-card">
        <div class="dashboard-header">
            <div class="page-title-section">
                <h3 class="page-title">{{'DASHBOARD'}}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>
            </div>
            
            <div class="date-filter-section">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary btn_days dropdown-toggle" data-toggle="dropdown">
                        <i class="fa-solid fa-calendar-days mr-1"></i> 
                        @if(request('date_range'))
                            {{ ucfirst(str_replace('_', ' ', request('date_range'))) }}
                        @else
                            Last 7 Days
                        @endif
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('dashboard', ['date_range' => 'today']) }}">Today</a>
                        <a class="dropdown-item" href="{{ route('dashboard', ['date_range' => 'last_7_days']) }}">Last 7 Days</a>
                        <a class="dropdown-item" href="{{ route('dashboard', ['date_range' => 'last_30_days']) }}">Last 30 Days</a>
                        <a class="dropdown-item" href="{{ route('dashboard', ['date_range' => 'this_month']) }}">This Month</a>
                        <a class="dropdown-item" href="{{ route('dashboard', ['date_range' => 'last_month']) }}">Last Month</a>
                    </div>
                </div>

                <form action="{{ route('dashboard') }}" method="GET" class="custom-date-picker">
                    <input type="hidden" name="date_range" value="custom">
                    <div class="d-flex align-items-center">
                        <input type="date" class="form-control" name="start_date" 
                            value="{{ request('start_date') }}" 
                            max="{{ date('Y-m-d') }}"
                            placeholder="Start Date">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" name="end_date" 
                            value="{{ request('end_date', date('Y-m-d')) }}" 
                            max="{{ date('Y-m-d') }}"
                            placeholder="End Date">
                        <button type="submit" class="btn btn-primary ml-2">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-primary">
                    <i class="fe fe-users"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$user->count()}}</h3>
                    <h6 class="text-muted mb-0">Users</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-primary">
                <i class="fas fa-users"></i>
                            </span>
                <div class="dash-count">
                    <h3>{{$activeUserPercentage }}%</h3>
                    <h6 class="text-muted mb-0">User Retention Rate (%)</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-primary">
                <i class="fas fa-users"></i>
                            </span>
                <div class="dash-count">
                    <h3>{{$recent_signup_user_count }}</h3>
                    <h6 class="text-muted mb-0">New Users</h6>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-success">
                <i class="fa-solid fa-computer-mouse"></i>
                            </span>
                <div class="dash-count">
                    <h3>{{$campaign_all->count()}}</h3>
                    <h6 class="text-muted mb-0">Clicks</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-danger">
                <i class="fa-solid fa-money-bill-transfer"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$number_of_conversion}}</h3>
                    <h6 class="text-muted mb-0">Conversions</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-danger">
                <i class="fa-solid fa-money-bill-transfer"></i>
                </span>
                <div class="dash-count">
                <h3>{{ ($campaign_all->count()!= 0) ? round(($number_of_conversion/$campaign_all->count())*100,2): 0}} % </h3>
                    <h6 class="text-muted mb-0">Conversions Rate %</h6>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-warning">
                <i class="fa-solid fa-sack-dollar"></i>
                </span>
                <div class="dash-count">
                    <h3>$6523</h3>
                    <h6 class="text-muted mb-0">Revenue</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-info">
                    <i class="fe fe-money"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$withdrawal_total}}</h3>
                    <h6 class="text-muted mb-0">Withdrawal Totla Request</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-success">
                    <i class="fe fe-money"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$withdrawal_approved}}</h3>
                    <h6 class="text-muted mb-0">Withdrawal Approved Request</h6>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-warning">
                    <i class="fe fe-money"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$withdrawal_pending}}</h3>
                    <h6 class="text-muted mb-0">Withdrawal Pending Request</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-danger">
                    <i class="fe fe-money"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$withdrawal_rejected}}</h3>
                    <h6 class="text-muted mb-0">Withdrawal Rejetced Request</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="stats-card">
            <div class="dash-widget-header">
                <span class="dash-widget-icon text-success">
                    <i class="fe fe-money"></i>
                </span>
                <div class="dash-count">
                    <h3>{{$total_payout}}</h3>
                    <h6 class="text-muted mb-0">Total Payout</h6>
                </div>
            </div>
        </div>
    </div>
    

</div>

<div class="row">
    <div class="col-lg-6">
        <div class="chart-container">
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

    <div class="col-lg-6">
        <div class="chart-container">
            <x-high-chart
                chart-id="chawwrt1"
                chart-type="column"
                title="Clicks Data" 
                subtitle=""
                :categories="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                :series="array_values($campaign)"
            />
        </div>
    </div>






<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recently Signed Up Users</h3>
                </div>
                <div class="card-body">
                    <table id="tickets-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($recent_signup_user as $key => $user)
                                 @if($key % 2 == 0)
                                    {{-- Even row --}}
                                        <tr class="even">
                                    @else
                                        {{-- Odd row --}}
                                        <tr class="even">
                                    @endif
                                        <td>{{ $key+1  }}</td>
                                        <td><img src="{{ $user->image }}" width="25" height="25" style="border-radius: 50%; object-fit: cover;" alt="User Image"></td>
                                        <td>{{ $user->name.$user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->dial_code.$user->mobile }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                               @endforeach
                         </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add any additional JavaScript here
});
</script>
@endpush



