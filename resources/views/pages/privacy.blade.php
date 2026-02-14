@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Privacy Policy</h2>

    <p>This Privacy Policy explains how Linkify collects, uses, and protects your information.</p>

    <h5 class="mt-4">Information We Collect</h5>
    <ul>
        <li>IP Address</li>
        <li>Device & Browser Information</li>
        <li>Referrer & Country Data</li>
    </ul>

    <h5 class="mt-4">How We Use Information</h5>
    <p>We use collected data for analytics and to improve our service.</p>

    <h5 class="mt-4">Security</h5>
    <p>Your data is securely stored and never sold to third parties.</p>

    <p class="mt-5 text-muted small">Last updated: {{ date('F Y') }}</p>
</div>
@endsection
