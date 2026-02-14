@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Terms & Conditions</h2>

    <p>By using Linkify, you agree to the following terms:</p>

    <ul>
        <li>You will not use the platform for illegal activities.</li>
        <li>You are responsible for content shared via shortened links.</li>
        <li>We reserve the right to suspend abusive accounts.</li>
    </ul>

    <h5 class="mt-4">Limitation of Liability</h5>
    <p>We are not responsible for third-party content accessed via shortened links.</p>

    <p class="mt-5 text-muted small">Effective date: {{ date('F Y') }}</p>
</div>
@endsection
