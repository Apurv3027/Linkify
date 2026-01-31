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

    </style>
</head>
<body>

    @include('layouts.header')

    <div class="container mt-5">

        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-bold">
                    👋 Welcome, {{ custom_user()->name }}
                </h3>
                <p class="text-muted">
                    Create, manage and track your links
                </p>
            </div>
        </div>

        <div class="row">
            {{-- CREATE LINK --}}
            <div class="col-md-5">
                <div class="card p-4 mb-4">
                    <h5 class="fw-bold mb-3">Create Short Link</h5>

                    <form method="POST" action="/shorten" enctype="multipart/form-data">
                        @csrf

                        <input type="url" name="original_url" class="form-control mb-3" placeholder="Paste URL">

                        <label class="small fw-semibold">Upload File (optional)</label>
                        <input type="file" name="file" class="form-control mb-3">

                        <button class="btn btn-primary w-100">
                            Create Link
                        </button>
                    </form>

                    {{-- @if(session('shortUrl'))
                    <div class="mt-3">
                        <input class="form-control" value="{{ session('shortUrl') }}" readonly>
                </div>
                @endif --}}
            </div>
        </div>

        {{-- MY LINKS --}}
        <div class="col-md-7">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">My Links</h5>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Short URL</th>
                            <th>Clicks</th>
                            <th>Type</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($links as $link)
                        <tr>
                            <td>
                                <a href="{{ url($link->short_code) }}" target="_blank">
                                    {{ url($link->short_code) }}
                                </a>
                            </td>
                            <td>{{ $link->clicks }}</td>
                            <td>{{ ucfirst($link->type) }}</td>
                            {{-- <td>
                                <form action="/links/{{ $link->id }}" method="POST" onsubmit="return confirm('Delete this link?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>
                            </form>
                            </td> --}}
                            <td class="text-end">
                                <button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal({{ $link->id }})">
                                    🗑 Delete
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
        </div>
    </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-bold">
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete this link?
                        <br>
                        <span class="text-muted small">This action cannot be undone.</span>
                    </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(linkId) {
            const form = document.getElementById('deleteForm');
            form.action = `/links/${linkId}`;

            const modal = new bootstrap.Modal(
                document.getElementById('deleteModal')
            );
            modal.show();
        }

    </script>

</body>
</html>
