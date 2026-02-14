@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Support</h2>

    <p>If you need help, feel free to contact us.</p>

    <div class="card p-4 mt-4 shadow-sm">
        <form method="POST" action="#">
            @csrf

            <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" class="form-control" placeholder="example@email.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Send Message
            </button>
        </form>
    </div>

    <div class="mt-4">
        <p class="text-muted small">
            Or email us directly at linkify@yopmail.com
        </p>
    </div>
</div>
@endsection
