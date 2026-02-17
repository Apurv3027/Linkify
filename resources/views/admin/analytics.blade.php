@extends('admin.layouts.app')

@section('title','Advanced Analytics')
@section('page-title','Analytics Overview')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-semibold mb-1">Performance Analytics</h4>
        <small class="text-secondary">Monitor traffic, growth & engagement insights</small>
    </div>

    <div class="d-flex align-items-center gap-2">
        <select id="dateRange" class="form-select form-select-sm">
            <option value="7" selected>Last 7 Days</option>
            <option value="15">Last 15 Days</option>
            <option value="30">Last 30 Days</option>
        </select>
    </div>
</div>


{{-- KPI SECTION --}}
<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm bg-body rounded-3 h-100">
            <div class="card-body">
                <div class="text-secondary small text-uppercase mb-2">
                    Total Clicks
                </div>
                <h3 id="kpiClicks" class="fw-bold mb-1">0</h3>
                <span id="kpiClicksGrowth" class="badge bg-success-subtle text-success small">
                    +0%
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm bg-body rounded-3 h-100">
            <div class="card-body">
                <div class="text-secondary small text-uppercase mb-2">
                    Total Users
                </div>
                <h3 id="kpiUsers" class="fw-bold mb-1">0</h3>
                <span id="kpiUsersGrowth" class="badge bg-success-subtle text-success small">
                    +0%
                </span>
            </div>
        </div>
    </div>

</div>


{{-- Main Chart --}}
<div class="card border-0 shadow-sm bg-body mb-4 rounded-3">
    <div class="card-header bg-body-tertiary border-0 px-4 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">Clicks vs Users Trend</h6>
        <div class="text-secondary small">Live Updates</div>
    </div>
    <div class="card-body p-4 position-relative">
        <div id="chartLoader" class="text-center py-5 d-none">
            <div class="spinner-border text-primary"></div>
        </div>
        <canvas id="comparisonChart" height="90"></canvas>
    </div>
</div>


<div class="row g-4">

    {{-- Top Performing Links --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-body rounded-3 h-100">
            <div class="card-header bg-body-tertiary border-0 px-4 py-3">
                <h6 class="fw-semibold mb-0">Top Performing Links</h6>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Short Code</th>
                            <th class="text-end">Clicks</th>
                        </tr>
                    </thead>
                    <tbody id="topLinksTable">
                        @foreach($topLinks as $link)
                        <tr>
                            <td>
                                <span class="badge bg-success">
                                    {{ $link->short_code }}
                                </span>
                            </td>
                            <td class="text-end fw-medium">
                                {{ number_format($link->clicks) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Geographic Analytics --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-body rounded-3 h-100">
            <div class="card-header bg-body-tertiary border-0 px-4 py-3">
                <h6 class="fw-semibold mb-0">Geographic Distribution</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="countryChart" height="90"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let comparisonChart;
    let countryChart;

    function showLoader(show = true){
        document.getElementById('chartLoader').classList.toggle('d-none', !show);
    }

    function initCharts(data) {

        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

        const ctx = document.getElementById('comparisonChart');
        const countryCtx = document.getElementById('countryChart');

        if (comparisonChart) comparisonChart.destroy();
        if (countryChart) countryChart.destroy();

        comparisonChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Clicks',
                        data: data.clicks,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,0.12)',
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Users',
                        data: data.users,
                        borderColor: '#20c997',
                        backgroundColor: 'rgba(32,201,151,0.10)',
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: isDark ? '#f8f9fa' : '#212529'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: isDark ? '#adb5bd' : '#495057' },
                        grid: { display:false }
                    },
                    y: {
                        ticks: { color: isDark ? '#adb5bd' : '#495057' },
                        grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.04)' }
                    }
                }
            }
        });

        countryChart = new Chart(countryCtx, {
            type: 'bar',
            data: {
                labels: data.countries,
                datasets: [{
                    label: 'Clicks',
                    data: data.countryClicks,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        updateKPIs(data);
    }

    function updateKPIs(data){

        const totals = data.totals;

        document.getElementById('kpiClicks').innerText =
            Number(totals.clicks).toLocaleString();

        document.getElementById('kpiUsers').innerText =
            Number(totals.users).toLocaleString();

        updateGrowthBadge('kpiClicksGrowth', totals.clickGrowth);
        updateGrowthBadge('kpiUsersGrowth', totals.userGrowth);
    }

    function updateGrowthBadge(elementId, value){

        const el = document.getElementById(elementId);

        el.classList.remove(
            'bg-success-subtle','text-success',
            'bg-danger-subtle','text-danger'
        );

        const isPositive = value >= 0;

        el.classList.add(
            isPositive ? 'bg-success-subtle' : 'bg-danger-subtle'
        );
        el.classList.add(
            isPositive ? 'text-success' : 'text-danger'
        );

        el.innerText = `${isPositive ? '+' : ''}${value}%`;
    }

    function loadAnalytics(days = 7){
        showLoader(true);

        fetch(`{{ route('admin.analytics.data') }}?days=${days}`)
            .then(res => res.json())
            .then(data => {
                initCharts(data);
                updateTopLinks(data.topLinks);
                showLoader(false);
            });
    }

    function updateTopLinks(links){
        let html = '';
        links.forEach(link=>{
            html += `
                <tr>
                    <td><span class="badge bg-success">${link.short_code}</span></td>
                    <td class="text-end fw-medium">${Number(link.clicks).toLocaleString()}</td>
                </tr>
            `;
        });
        document.getElementById('topLinksTable').innerHTML = html;
    }

    document.getElementById('dateRange').addEventListener('change', function(){
        loadAnalytics(this.value);
    });

    setInterval(()=>{
        loadAnalytics(document.getElementById('dateRange').value);
    },15000);

    loadAnalytics(7);

</script>
@endpush
