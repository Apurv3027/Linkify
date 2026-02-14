@extends('layouts.app')

@section('title', 'Dashboard | Linkify')

@push('styles')
<style>
    body {
        background: #f5f6fa;
    }

    .stats-card {
        padding: 25px;
        border-radius: 16px;
        text-align: center;
        color: #fff;
        transition: transform 0.2s;
    }

    .stats-card h3 {
        font-size: 2rem;
        margin-bottom: 0;
    }

    .stats-links {
        background: linear-gradient(135deg, #4f46e5, #6d5dfc);
    }

    .stats-clicks {
        background: linear-gradient(135deg, #14b8a6, #0dc0a0);
    }

    .stats-files {
        background: linear-gradient(135deg, #f59e0b, #fcb44f);
    }

    .analytics-stat {
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: 0.3s ease;
    }

    .chart-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
    }

    @media (max-width: 768px) {
        .chart-wrapper {
            height: 220px;
        }
    }

    .no-data {
        font-size: 15px;
        font-weight: 500;
    }

    .no-data {
        font-size: 16px;
        font-weight: 600;
        color: #cbd5e1;
    }
</style>
@endpush

@section('content')

{{-- Main Content --}}
<div class="container mt-4 mb-5">

    {{-- Welcome --}}
    <div class="card p-4 mb-4">
        <h4 class="fw-bold mb-1">Welcome, {{ custom_user()->name }}</h4>
        <p class="text-muted mb-0">
            Here's a quick overview of your links and recent traffic activity.
        </p>
    </div>

    {{-- Quick Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stats-card stats-links h-100">
                <h3>{{ $totalLinks }}</h3>
                <p>Total Links</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stats-card stats-clicks h-100">
                <h3>{{ $totalClicks }}</h3>
                <p>Total Clicks</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stats-card stats-files h-100">
                <h3>{{ $totalFiles }}</h3>
                <p>Total Files</p>
            </div>
        </div>
    </div>

    {{-- Create + My Links Row --}}
    <div class="row g-4 mb-4">

        {{-- Create Link --}}
        <div class="col-12 col-lg-4">
            <div class="card p-4 h-100">
                <h6 class="fw-bold mb-3 text-primary">Create New Link</h6>

                {{-- <form method="POST" action="/shorten" enctype="multipart/form-data"> --}}
                    <form id="shortenForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <input type="url" name="original_url" class="form-control"
                                placeholder="Paste your destination URL here">
                        </div>

                        <div class="mb-3">
                            <input type="file" name="file" class="form-control" accept="image/*,video/*">
                        </div>

                        <button class="btn btn-primary w-100">
                            Create Link
                        </button>
                    </form>
            </div>
        </div>

        {{-- My Links --}}
        <div class="col-12 col-lg-8">
            <div class="card p-4 h-100">
                <h6 class="fw-bold mb-3">My Links</h6>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Short Link</th>
                                <th>Clicks</th>
                                <th>Type</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($links as $link)
                            <tr>
                                {{-- <td class="text-truncate" style="max-width:200px;">
                                    <a href="javascript:void(0)" onclick="openFile('{{ $link->short_code }}')"
                                        class="fw-semibold text-decoration-none">
                                        {{ url($link->short_code) }}
                                    </a>
                                </td> --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- <a href="{{ url($link->short_code) }}" target="_blank">{{
                                            url($link->short_code) }}</a> --}}
                                        <a href="javascript:void(0)" onclick="openFile('{{ $link->short_code }}')">
                                            {{ url($link->short_code) }}
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm ms-2 btn-copy"
                                            data-link="{{ url($link->short_code) }}" title="Copy link">📋</button>
                                    </div>
                                </td>
                                <td>{{ $link->clicks }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ strtoupper($link->type) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="openDeleteModal({{ $link->id }})">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    You haven't created any links yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-2">
                    {{ $links->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- PROFESSIONAL ANALYTICS --}}
    <div class="mt-5">

        {{-- Section Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Analytics</h4>
                <small class="text-muted">
                    Review how your links are performing over time.
                </small>
            </div>

            <div>
                <select id="periodSelect" class="form-select form-select-sm" style="width:150px;">
                    <option value="7" {{ request('period')==7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ request('period')==30 ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="all" {{ request('period')=='all' ? 'selected' : '' }}>All Time</option>
                </select>
            </div>
        </div>

        {{-- Top Summary Stats --}}
        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="analytics-stat h-100">
                    <h5>Total Clicks</h5>
                    <h3>{{ $totalClicks }}</h3>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="analytics-stat h-100">
                    <h5>Unique Visitors</h5>
                    <h3>{{ $uniqueVisitors ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="analytics-stat h-100">
                    <h5>Top Country</h5>
                    <h3>{{ $topCountry ?: 'N/A' }}</h3>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="analytics-stat h-100">
                    <h5>Top Device</h5>
                    <h3>{{ $topDevice ?? 'N/A' }}</h3>
                </div>
            </div>

        </div>

        {{-- Charts Area --}}
        <div class="row g-3 row-cols-1 row-cols-md-2">

            {{-- Click Trend --}}
            <div class="col-12 col-md-6">
                <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                    <h6 class="fw-bold mb-3">Click Activity</h6>

                    <div class="chart-wrapper position-relative">
                        <canvas id="clicksChart"></canvas>
                        <div class="no-data text-muted text-center d-none
                                    position-absolute top-50 start-50 translate-middle">
                            No Data Available
                        </div>
                    </div>
                </div>
            </div>

            {{-- Country Distribution --}}
            <div class="col-12 col-md-6">
                <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                    <h6 class="fw-bold mb-3">Audience by Country</h6>

                    <div class="chart-wrapper position-relative">
                        <canvas id="countryChart"></canvas>
                        <div class="no-data text-muted text-center d-none
                                    position-absolute top-50 start-50 translate-middle">
                            No Data Available
                        </div>
                    </div>
                </div>
            </div>

            {{-- Referrer --}}
            <div class="col-12 col-md-6">
                <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                    <h6 class="fw-bold mb-3">Traffic Sources</h6>

                    <div class="chart-wrapper position-relative">
                        <canvas id="referrerChart"></canvas>
                        <div class="no-data text-muted text-center d-none
                                    position-absolute top-50 start-50 translate-middle">
                            No Data Available
                        </div>
                    </div>
                </div>
            </div>

            {{-- Device --}}
            <div class="col-12 col-md-6">
                <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                    <h6 class="fw-bold mb-3">Device Breakdown</h6>

                    <div class="chart-wrapper position-relative">
                        <canvas id="deviceChart"></canvas>
                        <div class="no-data text-muted text-center d-none
                                    position-absolute top-50 start-50 translate-middle">
                            No Data Available
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


{{-- Footer --}}
{{-- @include('layouts.footer') --}}

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title text-danger fw-bold">Confirm Delete</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                This action cannot be undone.
                Are you sure you want to delete this link?
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- File Preview Modal --}}
<div class="modal fade" id="fileModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4 text-center">

            <!-- CLOSE ICON -->
            <div class="d-flex justify-content-end">
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div id="fileContent">
                Loading...
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    const clicksChartCtx = document.getElementById('clicksChart').getContext('2d');
        const countryChartCtx = document.getElementById('countryChart').getContext('2d');
        const referrerChartCtx = document.getElementById('referrerChart').getContext('2d');
        const deviceChartCtx = document.getElementById('deviceChart').getContext('2d');

        toggleNoData('clicksChart', @json($values));
        if (@json($values).some(v => v > 0)) {
            const clicksChart = new Chart(clicksChartCtx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Clicks',
                        data: @json($values),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.15)',
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 6,
                                callback: function(value) {
                                    const label = this.getLabelForValue(value);
                                    return label.slice(5); // Shows only MM-DD
                                }
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        toggleNoData('countryChart', @json($values));
        if (@json($values).some(v => v > 0)) {
            const countryChart = new Chart(countryChartCtx, {
                type: 'pie',
                data: {
                    labels: @json($countries->pluck('country')),
                    datasets: [{
                        data: @json($countries->pluck('total')),
                        backgroundColor: [
                            '#4f46e5','#14b8a6','#f59e0b',
                            '#ef4444','#10b981','#6366f1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: window.innerWidth < 768 ? 'bottom' : 'right',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: window.innerWidth < 768 ? 11 : 13
                                }
                            }
                        }
                    }
                }
            });
        }

        toggleNoData('referrerChart', @json($values));
        if (@json($values).some(v => v > 0)) {
            const referrerChart = new Chart(referrerChartCtx, {
                type: 'bar',
                data: {
                    labels: @json(
                        $referrers->pluck('referrer')->map(fn($r) =>
                            parse_url($r, PHP_URL_HOST) ?? 'Direct'
                        )
                    ),
                    datasets: [{
                        data: @json($referrers->pluck('total')),
                        backgroundColor: '#4f46e5',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            ticks: {
                                autoSkip: false
                            }
                        }
                    }
                }
            });
        }

        toggleNoData('deviceChart', @json($values));
        if (@json($values).some(v => v > 0)) {
            const deviceChart = new Chart(deviceChartCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($devices->pluck('device')),
                    datasets: [{
                        data: @json($devices->pluck('total')),
                        backgroundColor: [
                            '#6366f1','#10b981','#f59e0b','#ef4444'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: window.innerWidth < 768 ? 'bottom' : 'right',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: window.innerWidth < 768 ? 11 : 13
                                }
                            }
                        }
                    }
                }
            });
        }

        // 🔄 Poll every 5 seconds
        setInterval(() => {
            fetch("{{ route('analytics.data') }}")
                .then(res => res.json())
                .then(data => {
                    clicksChart.data.labels = data.labels;
                    clicksChart.data.datasets[0].data = data.values;
                    clicksChart.update();
                });
        }, 5000);

        // Copy to clipboard
        document.querySelectorAll('.btn-copy').forEach(btn => {
            btn.addEventListener('click', () => {
                const link = btn.getAttribute('data-link');
                navigator.clipboard.writeText(link).then(() => {
                    btn.textContent = '✅';
                    setTimeout(() => btn.textContent = '📋', 1000);
                });
            });
        });

        // Delete modal
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/links/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function openFile(code) {
            fetch('/' + code, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {

                    // URL redirect
                    if (data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }

                    let html = '';

                    if (data.type === 'image') {
                        html = `
                    <img src="${data.url}" class="img-fluid rounded mb-3" style="max-height:320px">
                `;
                    } else {
                        html = `
                    <video src="${data.url}" controls class="w-100 rounded mb-3" style="max-height:320px"></video>
                `;
                    }

                    html += `
                <p class="text-muted">👁️ ${data.views} | ⬇ ${data.downloads}</p>
                <a href="${data.downloadUrl}" class="btn btn-primary w-100 mt-2">
                    ⬇ Download
                </a>
            `;

                    document.getElementById('fileContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('fileModal')).show();
                })
                .catch(() => {
                    alert('Failed to load preview');
                    console.error('Error:', error);
                });
        }

        // Period filter
        document.getElementById('periodSelect').addEventListener('change', function() {

            const period = this.value;

            fetch("{{ route('analytics.data') }}?period=" + period)
                .then(res => res.json())
                .then(data => {

                    // Update Click Trend Chart
                    clicksChart.data.labels = data.labels;
                    clicksChart.data.datasets[0].data = data.values;
                    clicksChart.update();

                    // Update Total Clicks
                    document.querySelectorAll('.analytics-stat h3')[0].innerText = data.totalClicks;

                    // Update Unique Visitors
                    document.querySelectorAll('.analytics-stat h3')[1].innerText = data.uniqueVisitors;

                    // Update Country Chart
                    countryChart.data.labels = data.countries.map(c => c.country);
                    countryChart.data.datasets[0].data = data.countries.map(c => c.total);
                    countryChart.update();

                    // Update Device Chart
                    deviceChart.data.labels = data.devices.map(d => d.device);
                    deviceChart.data.datasets[0].data = data.devices.map(d => d.total);
                    deviceChart.update();

                    // Update Referrer Chart
                    referrerChart.data.labels = data.referrers.map(r => r.referrer);
                    referrerChart.data.datasets[0].data = data.referrers.map(r => r.total);
                    referrerChart.update();

                    toggleNoData('clicksChart', data.values);
                    toggleNoData('countryChart', data.countries.map(c => c.total));
                    toggleNoData('deviceChart', data.devices.map(d => d.total));
                    toggleNoData('referrerChart', data.referrers.map(r => r.total));

                });
        });

        document.getElementById('shortenForm').addEventListener('submit', function(e) {

            e.preventDefault();

            const formData = new FormData(this);

            fetch('/shorten', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.text())
            .then(() => {
                location.reload(); // or dynamically append new row if you want pro version
            });
        });

        function toggleNoData(chartId, dataArray) {
            const canvas = document.getElementById(chartId);
            const wrapper = canvas.closest('.chart-wrapper');
            const noDataDiv = wrapper.querySelector('.no-data');

            const hasData = dataArray && dataArray.length && dataArray.some(v => v > 0);

            if (!hasData) {
                canvas.style.display = 'none';
                noDataDiv.classList.remove('d-none');
            } else {
                canvas.style.display = 'block';
                noDataDiv.classList.add('d-none');
            }
        }

</script>

@endpush
