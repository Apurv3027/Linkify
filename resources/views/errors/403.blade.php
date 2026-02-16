@extends('layouts.app')

@section('title', 'Unauthorized Access')

@section('content')
<div class="container py-5 text-center">

    <div class="mb-4">
        <i class="fa fa-lock text-danger" style="font-size: 80px;"></i>
    </div>

    <h1 class="display-4 fw-bold text-danger">403</h1>

    <p class="lead">
        Unauthorized Access
    </p>

    {{-- <p class="text-muted">
        You do not have permission to access this page.
        Please contact the administrator if you believe this is an error.
    </p> --}}

    <p class="text-muted">
        {{ $exception->getMessage() ?: 'Access denied. You are not authorized to access this page.' }}
    </p>

    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-primary px-4 me-2">
            Go Back Home
        </a>

        @auth
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">
            Go to Dashboard
        </a>
        @endauth
    </div>

</div>
@endsection
