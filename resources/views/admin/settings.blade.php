@extends('admin.layouts.app')

@section('title','Account Settings')
@section('page-title','Account Settings')

@section('content')

<div class="container-fluid">
    <div class="row g-4">

        {{-- LEFT SIDE : Personal Info --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm rounded-4 bg-body h-100">

                <div class="card-header bg-body border-bottom py-3 d-flex align-items-center">
                    <i class="fa fa-user text-primary me-2"></i>
                    <h6 class="fw-semibold mb-0 text-body">
                        Personal Information
                    </h6>
                </div>

                <div class="card-body p-4">

                    <div class="mb-4">
                        <label class="form-label text-body-secondary fw-medium">
                            Full Name
                        </label>
                        <div class="form-control bg-body text-body border rounded-3">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-body-secondary fw-medium">
                            Email Address
                        </label>
                        <div class="form-control bg-body text-body border rounded-3">
                            {{ $user->email }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT SIDE : Change Password --}}
        <div class="col-lg-7">

            {{-- Alerts --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger shadow-sm rounded-3">
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 bg-body">

                <div class="card-header bg-body border-bottom py-3 d-flex align-items-center">
                    <i class="fa fa-lock text-danger me-2"></i>
                    <h6 class="fw-semibold mb-0 text-body">
                        Change Password
                    </h6>
                </div>

                <div class="card-body p-4">

                    <form method="POST" action="{{ route('admin.password.update') }}">
                        @csrf

                        {{-- Current Password --}}
                        <div class="mb-4">
                            <label class="form-label text-body-secondary fw-medium">
                                Current Password
                            </label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control bg-body text-body border rounded-start-3" placeholder="Enter current password" required>
                                <span class="input-group-text bg-body border rounded-end-3 toggle-password"
                                    data-target="current_password" style="cursor:pointer;">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div class="mb-4">
                            <label class="form-label text-body-secondary fw-medium">
                                New Password
                            </label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control bg-body text-body border rounded-start-3" placeholder="Enter new password" required>
                                <span class="input-group-text bg-body border rounded-end-3 toggle-password"
                                    data-target="password">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label class="form-label text-body-secondary fw-medium">
                                Confirm Password
                            </label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control bg-body text-body border rounded-start-3" placeholder="Enter confirm password" required>
                                <span class="input-group-text bg-body border rounded-end-3 toggle-password"
                                    data-target="password_confirmation">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                Update Password
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            let input = document.getElementById(this.dataset.target);
            let icon = this.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endpush
