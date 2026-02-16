@extends('admin.layouts.app')

@section('title','Analytics')
@section('page-title','Analytics Overview')

@section('content')

<div class="card shadow-sm p-4">
    <canvas id="clickChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('clickChart');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($clicks->pluck('date')) !!},
            datasets: [{
                label: 'Daily Clicks',
                data: {!! json_encode($clicks->pluck('total')) !!},
                borderWidth: 2,
                tension: 0.3
            }]
        }
    });
</script>

@endsection
