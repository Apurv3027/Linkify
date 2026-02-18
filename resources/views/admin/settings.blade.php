@extends('admin.layouts.app')

@section('title','Settings')
@section('page-title','Platform Settings')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-8">

        <div class="card border rounded-4 shadow-sm bg-body">

            <div class="card-header bg-body border-bottom py-3 rounded-top-4">
                <h5 class="mb-1 fw-semibold text-body">
                    General Configuration
                </h5>
                <small class="text-body-secondary">
                    Manage your platform basic information
                </small>
            </div>

            <div class="card-body p-4">

                {{-- Success Message --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf

                    {{-- Platform Name --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-body">
                            Platform Name
                        </label>

                        <input type="text" name="platform_name"
                            class="form-control form-control-lg rounded-3 bg-body text-body border"
                            value="{{ old('platform_name', $setting->platform_name ?? '') }}"
                            placeholder="Enter platform name" required>

                        <small class="text-body-secondary">
                            Displayed across dashboards, emails and system pages.
                        </small>
                    </div>

                    {{-- Admin Email --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-body">
                            Admin Email
                        </label>

                        <input type="email" name="admin_email"
                            class="form-control form-control-lg rounded-3 bg-body text-body border"
                            value="{{ old('admin_email', $setting->admin_email ?? '') }}"
                            placeholder="admin@example.com">

                        <small class="text-body-secondary">
                            Used for alerts and system notifications.
                        </small>
                    </div>

                    <div class="d-flex justify-content-end pt-2 border-top">
                        <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                            <i class="fa fa-save me-2"></i> Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
