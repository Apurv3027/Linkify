@extends('admin.layouts.app')

@section('title','Dashboard')
@section('page-title','Dashboard Overview')

@section('content')

{{-- Welcome Section --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Welcome back, {{ auth()->user()->name }}</h4>
            <p class="text-muted mb-0">Here's what's happening with Linkify today.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">
                {{ now()->format('l, d M Y') }}
            </small>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-4 mb-4">

    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fa fa-users text-primary fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Users</h6>
                    <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fa fa-link text-success fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Links</h6>
                    <h3 class="fw-bold mb-0">{{ $totalLinks }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fa fa-chart-line text-warning fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Clicks</h6>
                    <h3 class="fw-bold mb-0">{{ $totalClicks }}</h3>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Quick Info Section --}}
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">System Status</h5>

        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Platform:</strong> Linkify URL Shortener</p>
                <p class="mb-2"><strong>Admin Email:</strong> {{ auth()->user()->email }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Environment:</strong> {{ app()->environment() }}</p>
                <p class="mb-2"><strong>Version:</strong> 1.0.0</p>
            </div>
        </div>

    </div>
</div>

@endsection
