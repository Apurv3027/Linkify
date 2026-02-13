<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard | Linkify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        /* Header */
        .app-header {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        /* Dashboard Cards */
        .card {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .badge-file {
            padding: 0.35em 0.65em;
            font-size: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f0f0;
        }

        .btn-copy {
            transition: background 0.2s;
        }

        .btn-copy:hover {
            background-color: #e0e0e0;
        }

        .list-group-item {
            border-radius: 8px;
            margin-bottom: 5px;
            transition: background 0.2s;
        }

        .list-group-item:hover {
            background-color: #f0f0f0;
        }

        /* Footer */
        footer {
            margin-top: 50px;
            padding: 20px 0;
            text-align: center;
            color: #888;
        }

        /* Quick stats */
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

        .stats-card p {
            font-size: 0.9rem;
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
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: 0.3s ease;
        }

        .analytics-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .analytics-stat h5 {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 6px;
        }

        .analytics-stat h3 {
            font-weight: 700;
            margin: 0;
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
            width: 100%;
        }

        /* Mobile adjust */
        @media (max-width: 768px) {
            .chart-wrapper {
                height: 280px;
            }
        }
    </style>
</head>

<body>

    {{-- Header --}}
    @include('layouts.header')

    {{-- Main Content --}}
    <div class="container mt-4 mb-5">

        {{-- Welcome --}}
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1">👋 Welcome back, {{ custom_user()->name }}</h4>
            <p class="text-muted mb-0">Track performance, manage links and analyze traffic.</p>
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
                    <h6 class="fw-bold mb-3 text-primary">➕ Create Short Link</h6>

                    {{-- <form method="POST" action="/shorten" enctype="multipart/form-data"> --}}
                        <form id="shortenForm" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <input type="url" name="original_url" class="form-control"
                                    placeholder="https://example.com">
                            </div>

                            <div class="mb-3">
                                <input type="file" name="file" class="form-control" accept="image/*,video/*">
                            </div>

                            <button class="btn btn-primary w-100">
                                Generate Link
                            </button>
                        </form>
                </div>
            </div>

            {{-- My Links --}}
            <div class="col-12 col-lg-8">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold mb-3">📎 My Links</h6>

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
                                        No links created yet
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
                    <h4 class="fw-bold mb-0">📊 Analytics Overview</h4>
                    <small class="text-muted">Traffic insights & performance metrics</small>
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

                <div class="col-6 col-md-3">
                    <div class="analytics-stat h-100">
                        <h5>Total Clicks</h5>
                        <h3>{{ $totalClicks }}</h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="analytics-stat h-100">
                        <h5>Unique Visitors</h5>
                        <h3>{{ $uniqueVisitors ?? 0 }}</h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="analytics-stat h-100">
                        <h5>Top Country</h5>
                        <h3>{{ $topCountry ?: 'N/A' }}</h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="analytics-stat h-100">
                        <h5>Top Device</h5>
                        <h3>{{ $topDevice ?? 'N/A' }}</h3>
                    </div>
                </div>

            </div>

            {{-- Charts Area --}}
            <div class="row g-4">

                {{-- Click Trend --}}
                <div class="col-12 col-md-6">
                    <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                        <h6 class="fw-bold mb-3">📈 Click Trend</h6>

                        <div class="chart-wrapper">
                            <canvas id="clicksChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Country Distribution --}}
                <div class="col-12 col-md-6">
                    <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                        <h6 class="fw-bold mb-3">🌍 Country Distribution</h6>

                        <div class="chart-wrapper">
                            <canvas id="countryChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Referrer --}}
                <div class="col-12 col-md-6">
                    <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                        <h6 class="fw-bold mb-3">🌐 Traffic Sources</h6>

                        <div class="chart-wrapper">
                            <canvas id="referrerChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Device --}}
                <div class="col-12 col-md-6">
                    <div class="card p-4 shadow-sm border-0 rounded-4 h-100">
                        <h6 class="fw-bold mb-3">💻 Device Types</h6>

                        <div class="chart-wrapper">
                            <canvas id="deviceChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-bold">Confirm Delete</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    This link will be permanently removed.
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

    <script>
        const clicksChartCtx = document.getElementById('clicksChart').getContext('2d');
        const countryChartCtx = document.getElementById('countryChart').getContext('2d');
        const referrerChartCtx = document.getElementById('referrerChart').getContext('2d');
        const deviceChartCtx = document.getElementById('deviceChart').getContext('2d');

        const clicksChart = new Chart(clicksChartCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Clicks',
                    data: @json($values),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const referrerChart = new Chart(referrerChartCtx, {
            type: 'bar',
            data: {
                labels: @json($referrers->pluck('referrer')),
                datasets: [{
                    label: 'Clicks',
                    data: @json($referrers->pluck('total')),
                    backgroundColor: '#4f46e5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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

    </script>

</body>

</html>
