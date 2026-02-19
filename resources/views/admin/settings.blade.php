@extends('admin.layouts.app')

@section('title','Platform Settings')
@section('page-title','System Configuration')

@section('content')

<div class="container-fluid px-0">

    <div class="row g-4">

        {{-- App Identity Card --}}
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm rounded-4 bg-body h-100">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-primary-subtle text-primary rounded-3 me-3">
                            <i class="fa fa-globe fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-0 text-body">Application Identity</h6>
                            <small class="text-body-secondary">
                                General platform configuration
                            </small>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">Application Name</span>
                            <span class="text-body-secondary">
                                {{ config('app.name') }}
                            </span>
                        </li>

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">Application URL</span>
                            <span class="text-body-secondary small">
                                {{ config('app.url') }}
                            </span>
                        </li>

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">Locale</span>
                            <span class="badge bg-info-subtle text-info">
                                {{ strtoupper(config('app.locale')) }}
                            </span>
                        </li>

                    </ul>

                </div>
            </div>

        </div>

        {{-- Environment Card --}}
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm rounded-4 bg-body h-100">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-success-subtle text-success rounded-3 me-3">
                            <i class="fa fa-server fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-0 text-body">Environment Status</h6>
                            <small class="text-body-secondary">
                                System runtime information
                            </small>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">Environment</span>
                            <span class="badge
                                {{ config('app.env') === 'production'
                                    ? 'bg-success-subtle text-success'
                                    : 'bg-warning-subtle text-warning' }}">
                                {{ ucfirst(config('app.env')) }}
                            </span>
                        </li>

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">Debug Mode</span>
                            <span class="badge
                                {{ config('app.debug')
                                    ? 'bg-danger-subtle text-danger'
                                    : 'bg-success-subtle text-success' }}">
                                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                            </span>
                        </li>

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">Laravel Version</span>
                            <span class="text-body-secondary">
                                {{ app()->version() }}
                            </span>
                        </li>

                        <li class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between">
                            <span class="text-body fw-medium">PHP Version</span>
                            <span class="text-body-secondary">
                                {{ PHP_VERSION }}
                            </span>
                        </li>

                    </ul>

                </div>
            </div>

        </div>

    </div>

    {{-- Footer Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-body mt-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h6 class="fw-semibold mb-1 text-body">Configuration Management</h6>
                <small class="text-body-secondary">
                    Core settings are managed via environment variables (.env file).
                </small>
            </div>

            <div class="text-end">
                <code class="text-body-secondary small">
                    php artisan optimize:clear
                </code>
            </div>

        </div>
    </div>

</div>

@endsection
