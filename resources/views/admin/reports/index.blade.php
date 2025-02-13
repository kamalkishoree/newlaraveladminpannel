@extends('admin.layouts.master')

@section('page_title')
{{__('Reports')}}
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
                <h3 class="page-title">{{'Reports'}}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active-breadcrumb">
                        <a href="{{ route('report.index') }}">Reports</a>
                    </li>
                </ul>
            </div>
           
            </div>
        </div>
    </div><!-- /card finish -->



<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- route(report.user)-->
                <a class="btn btn-success mx-5" href="{{route('report.user')}}"><span  class="pr-2">Export User </span><i class="fa-sharp fa-solid fa-file-excel"></i> </a>
                <a class="btn btn-success mx-5" href="{{route('report.click')}}"><span class="pr-2">Export Clicks </span><i class="fa-sharp fa-solid fa-file-excel"></i></a>
                </div>

            </div>
        </div>
    </div>

    </div><!-- /Page Header -->



@endsection




@push('css')

@endpush

@push('scripts')

@endpush



