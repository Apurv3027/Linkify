@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-4 fw-bold">404</h1>
    <p class="lead">Oops! The page you are looking for does not exist.</p>

    <a href="{{ url('/') }}" class="btn btn-primary mt-3">
        Go Back Home
    </a>
</div>
@endsection
