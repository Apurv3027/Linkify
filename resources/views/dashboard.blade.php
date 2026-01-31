<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Linkify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #f4f6fb;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
        }

        .badge-file {
            background: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

    </style>
</head>

<body>

    @include('layouts.header')

    <div class="container mt-4">

        {{-- HEADER --}}
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1">
                👋 Welcome, {{ custom_user()->name }}
            </h4>
            <p class="text-muted mb-0">
                Create, manage & track your URLs and files
            </p>
        </div>

        <div class="row">

            {{-- CREATE LINK --}}
            <div class="col-md-4">
                <div class="card p-4 mb-4">
                    <h6 class="fw-bold mb-3">➕ Create Short Link</h6>

                    <form method="POST" action="/shorten" enctype="multipart/form-data">
                        @csrf

                        <input type="url" name="original_url" class="form-control mb-3" placeholder="Paste long URL (optional)">

                        <label class="small fw-semibold">Upload file (optional)</label>
                        <input type="file" name="file" class="form-control mb-3" accept="image/*,video/*">

                        <button class="btn btn-primary w-100">
                            Create Link
                        </button>
                    </form>
                </div>
            </div>

            {{-- MY LINKS --}}
            <div class="col-md-8">
                <div class="card p-4">
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
                                @php
                                $extension = $link->file_path
                                ? strtolower(pathinfo($link->file_path, PATHINFO_EXTENSION))
                                : null;

                                $isImage = in_array($extension, ['jpg','jpeg','png','webp']);
                                $isVideo = in_array($extension, ['mp4','mov','avi']);

                                $fileSize = $link->file_path
                                ? round(Storage::disk('public')->size($link->file_path) / 1024 / 1024, 2)
                                : null;
                                @endphp

                                <tr>
                                    <td>
                                        <a href="{{ url($link->short_code) }}" target="_blank" class="fw-semibold">
                                            {{ url($link->short_code) }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $link->clicks }}
                                    </td>

                                    <td>
                                        @if($link->type === 'url')
                                        <span class="badge badge-file">
                                            🔗 URL
                                        </span>
                                        @else
                                        <span class="badge badge-file">
                                            @if($isImage) 🖼 Image @elseif($isVideo) 🎥 Video @else 📁 File @endif
                                        </span>
                                        <div class="small text-muted">
                                            {{ $fileSize }} MB
                                        </div>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal({{ $link->id }})">
                                            🗑 Delete
                                        </button>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No links created yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-bold">
                        Confirm Delete
                    </h5>
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
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/links/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

    </script>

</body>
</html>
