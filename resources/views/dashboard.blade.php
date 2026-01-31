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

    </style>
</head>

<body>

    {{-- Header --}}
    @include('layouts.header')

    {{-- Main Content --}}
    <div class="container mt-4 mb-5">

        {{-- Welcome --}}
        <div class="card p-4 mb-4">
            <h4 class="fw-bold">👋 Welcome back, {{ custom_user()->name }}</h4>
            <p class="text-muted mb-0">Manage your short links, track clicks, and upload files easily.</p>
        </div>

        {{-- Quick Stats --}}
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="stats-card stats-links">
                    <h3>{{ $totalLinks }}</h3>
                    <p>Total Links</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card stats-clicks">
                    <h3>{{ $totalClicks }}</h3>
                    <p>Total Clicks</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card stats-files">
                    <h3>{{ $totalFiles }}</h3>
                    <p>Total Files</p>
                </div>
            </div>
        </div>

        {{-- Create Short Link --}}
        <div class="col mb-4">
            <div class="card shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3 text-primary">➕ Create Short Link</h5>
                <p class="text-muted small mb-3">Paste a long URL or upload a file to generate a short link instantly.</p>

                <form method="POST" action="/shorten" enctype="multipart/form-data">
                    @csrf

                    {{-- URL Input --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Long URL (optional)</label>
                        <input type="url" name="original_url" class="form-control" placeholder="https://example.com">
                    </div>

                    {{-- File Upload --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload File (optional)</label>
                        <input type="file" name="file" class="form-control" accept="image/*,video/*">
                        <small class="text-muted">Supported: JPG, PNG, MP4, MOV, etc.</small>
                    </div>

                    {{-- Submit Button --}}
                    <button class="btn btn-primary w-100 py-2">
                        <i class="bi bi-link-45deg me-1"></i> Create Link
                    </button>
                </form>
            </div>
        </div>



        {{-- Main Row --}}
        <div class="row g-4">
            {{-- Left: Links Table --}}
            <div class="col-lg-8">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">📎 My Links</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url($link->short_code) }}" target="_blank">{{ url($link->short_code) }}</a>
                                            <button class="btn btn-outline-secondary btn-sm ms-2 btn-copy" data-link="{{ url($link->short_code) }}" title="Copy link">📋</button>
                                        </div>
                                    </td>
                                    <td>{{ $link->clicks }}</td>
                                    <td>
                                        @if($link->type==='url')
                                        <span class="badge badge-file" style="background:#c7d2fe;color:#3730a3;">🔗 URL</span>
                                        @elseif(in_array(strtolower(pathinfo($link->file_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))
                                        <span class="badge badge-file" style="background:#d1fae5;color:#065f46;">🖼 Image</span>
                                        @elseif(in_array(strtolower(pathinfo($link->file_path, PATHINFO_EXTENSION)), ['mp4','mov','avi']))
                                        <span class="badge badge-file" style="background:#ffe7c0;color:#78350f;">🎥 Video</span>
                                        @else
                                        <span class="badge badge-file" style="background:#e0e7ff;color:#3730a3;">📁 File</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal({{ $link->id }})">🗑 Delete</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No links created yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">{{ $links->links() }}</div>
                    </div>
                </div>
            </div>

            {{-- Right: Chart & Recent --}}
            <div class="col-lg-4">
                <div class="card p-4 mb-4">
                    <h6 class="fw-bold mb-3">📊 Clicks Over Time</h6>
                    <canvas id="clicksChart" height="200"></canvas>
                </div>

                {{-- <div class="card p-4">
                    <h6 class="fw-bold mb-3">🕒 Recent Activity</h6>
                    <ul class="list-group list-group-flush">
                        @forelse($recentLinks as $link)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ url($link->short_code) }}" target="_blank">{{ $link->short_code }}</a>
                            <span class="badge bg-primary rounded-pill">{{ $link->created_at->diffForHumans() }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-muted">No recent activity</li>
                        @endforelse
                    </ul>
                </div> --}}
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

    <script>
        const ctx = document.getElementById('clicksChart').getContext('2d');
        new Chart(ctx, {
            type: 'line'
            , data: {
                labels: @json($chartLabels)
                , datasets: [{
                    label: 'Clicks'
                    , data: @json($chartValues)
                    , borderColor: '#4f46e5'
                    , backgroundColor: 'rgba(79,70,229,0.2)'
                    , tension: 0.3
                    , fill: true
                }]
            }
            , options: {
                responsive: true
                , plugins: {
                    legend: {
                        display: false
                    }
                }
                , scales: {
                    y: {
                        beginAtZero: true
                        , precision: 0
                    }
                }
            }
        });

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

    </script>

</body>
</html>
