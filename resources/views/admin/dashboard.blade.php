@extends('admin.layouts.app')

@section('title','Dashboard')
@section('page-title','Dashboard Overview')

@section('content')

{{-- Welcome Section --}}
<div class="card border-0 shadow-sm bg-body mb-4 rounded-3">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h4 class="fw-semibold mb-1 text-body">
                Welcome back, {{ auth()->user()->name }}
            </h4>
            <small class="text-secondary">
                Here's what’s happening with your platform today.
            </small>
        </div>

        <div class="text-md-end">
            <div class="small text-secondary">
                {{ now()->format('l, d M Y') }}
            </div>
        </div>

    </div>
</div>


{{-- Stats Overview --}}
<div class="row g-4 mb-4">

    {{-- Total Users --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm bg-body h-100 rounded-3">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div>
                    <h6 class="text-secondary text-uppercase small mb-2">
                        Total Users
                    </h6>
                    <h3 class="fw-bold mb-0 text-body">
                        {{ number_format($totalUsers) }}
                    </h3>
                </div>

                <div
                    class="icon-wrapper bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                    <i class="fa fa-users"></i>
                </div>

            </div>
        </div>
    </div>

    {{-- Total Links --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm bg-body h-100 rounded-3">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div>
                    <h6 class="text-secondary text-uppercase small mb-2">
                        Total Links
                    </h6>
                    <h3 class="fw-bold mb-0 text-body">
                        {{ number_format($totalLinks) }}
                    </h3>
                </div>

                <div
                    class="icon-wrapper bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center">
                    <i class="fa fa-link"></i>
                </div>

            </div>
        </div>
    </div>

    {{-- Total Clicks --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm bg-body h-100 rounded-3">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div>
                    <h6 class="text-secondary text-uppercase small mb-2">
                        Total Clicks
                    </h6>
                    <h3 class="fw-bold mb-0 text-body">
                        {{ number_format($totalClicks) }}
                    </h3>
                </div>

                <div
                    class="icon-wrapper bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center">
                    <i class="fa fa-chart-line"></i>
                </div>

            </div>
        </div>
    </div>

</div>


{{-- System Information --}}
<div class="card border-0 shadow-sm bg-body rounded-3">
    <div class="card-header bg-body-tertiary border-0 py-3 px-4">
        <h6 class="fw-semibold mb-0 text-body">
            System Information
        </h6>
    </div>

    <div class="card-body px-4">

        <div class="row g-4">

            <div class="col-md-6">
                <div class="mb-3">
                    <small class="text-secondary d-block">Platform</small>
                    <span class="fw-medium text-body">Linkify URL Shortener</span>
                </div>

                <div class="mb-3">
                    <small class="text-secondary d-block">Admin Email</small>
                    <span class="fw-medium text-body">
                        {{ auth()->user()->email }}
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <small class="text-secondary d-block">Environment</small>
                    <span class="badge bg-info-subtle text-info px-3 py-2">
                        {{ app()->environment() }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-secondary d-block">Version</small>
                    <span class="fw-medium text-body">1.0.0</span>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
