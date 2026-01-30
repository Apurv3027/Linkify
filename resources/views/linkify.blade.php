<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Linkify | URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            border-radius: 18px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: none;
        }

        .brand {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #4f46e5;
        }

        .form-control-lg {
            padding: 14px 16px;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.95;
        }

        .copy-btn {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .table {
            font-size: 0.9rem;
        }

        footer,
        .text-white.small {
            opacity: 0.9;
        }

    </style>
</head>

<body>
    <div class="container px-3 my-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="card p-4">
                    <div class="text-center mb-3">
                        <div class="brand">Linkify</div>
                        <p class="text-muted mb-0">
                            Shorten links. Share smarter.
                        </p>
                    </div>

                    <!-- Shorten Form -->
                    <form method="POST" action="/shorten" enctype="multipart/form-data">
                        @csrf

                        <input type="url" name="original_url" class="form-control form-control-lg mb-3" placeholder="Paste a long URL to shorten">

                        <div class="text-center text-muted mb-3 small">
                            — or upload a file —
                        </div>

                        <input type="file" name="file" class="form-control mb-3" accept="image/*,video/*">

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Create Short Link
                        </button>
                    </form>

                    <!-- Short URL Result -->
                    @if(session('shortUrl'))
                    <div class="mt-4">
                        <label class="fw-semibold mb-1">Your short link</label>
                        <div class="input-group mb-3">
                            <input id="shortUrl" class="form-control" value="{{ session('shortUrl') }}" readonly>
                            <button class="btn btn-outline-primary copy-btn" onclick="copyUrl()">
                                Copy
                            </button>
                        </div>

                        <small id="copyMsg" class="text-success d-none">
                            ✔ Copied to clipboard
                        </small>
                    </div>
                    @endif

                    <!-- History Dashboard -->
                    @if(isset($links) && $links->count())
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Recent activity</h5>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Short / Preview</th>
                                    <th>Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($links as $link)
                                <tr>
                                    <td>
                                        @if($link->type === 'file')
                                        @php
                                        $fileUrl = asset('storage/' . $link->file_path);
                                        $extension = pathinfo($link->file_path, PATHINFO_EXTENSION);
                                        @endphp

                                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))
                                        <!-- IMAGE PREVIEW -->
                                        {{-- <a href="{{ url($link->short_code) }}" target="_blank">
                                        <img src="{{ $fileUrl }}" alt="image" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        </a> --}}
                                        <a href="{{ url($link->short_code) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            🏙️ View Photo
                                        </a>
                                        @else
                                        <!-- VIDEO FILE -->
                                        <a href="{{ url($link->short_code) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            🎥 View Video
                                        </a>
                                        @endif
                                        @else
                                        <!-- NORMAL URL -->
                                        {{-- <a href="{{ url($link->short_code) }}" target="_blank">
                                        {{ config('app.url') }}/{{ $link->short_code }}
                                        </a> --}}
                                        <a href="{{ url($link->short_code) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            🔗 View URL
                                        </a>
                                        @endif
                                    </td>
                                    <td>{{ $link->clicks }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <p class="text-center text-white small mt-3">
                    © {{ date('Y') }} Linkify • Built with Laravel
                </p>

            </div>
        </div>
    </div>

    <script>
        function copyUrl() {
            const input = document.getElementById('shortUrl');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);

            const msg = document.getElementById('copyMsg');
            msg.classList.remove('d-none');
            setTimeout(() => msg.classList.add('d-none'), 2000);
        }

    </script>
</body>
</html>
